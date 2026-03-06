<?php

namespace Aliziodev\Wilayah\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SearchService
{
    public function __construct(
        protected CacheService $cache
    ) {}

    /**
     * Cari nama wilayah di semua 4 level.
     * Mengembalikan Collection terurut: Provinsi → Kab → Kec → Desa.
     */
    public function searchAll(string $query): array
    {
        if (strlen($query) < config('wilayah.search.min_length', 2)) {
            return ['provinces' => collect(), 'regencies' => collect(), 'districts' => collect(), 'villages' => collect()];
        }

        $cacheKey = 'search:all:'.strtolower($query);

        return $this->cache->remember($cacheKey, function () use ($query) {
            $term = '%'.$query.'%';

            $provinces = app(config('wilayah.models.province'))::where('name', 'LIKE', $term)
                ->get()
                ->each(fn ($m) => $m->setAttribute('level', 'province'));

            $regencies = app(config('wilayah.models.regency'))::where('name', 'LIKE', $term)
                ->get()
                ->each(fn ($m) => $m->setAttribute('level', 'regency'));

            $districts = app(config('wilayah.models.district'))::where('name', 'LIKE', $term)
                ->get()
                ->each(fn ($m) => $m->setAttribute('level', 'district'));

            $villages = app(config('wilayah.models.village'))::where('name', 'LIKE', $term)
                ->get()
                ->each(fn ($m) => $m->setAttribute('level', 'village'));

            return [
                'provinces' => $provinces,
                'regencies' => $regencies,
                'districts' => $districts,
                'villages' => $villages,
            ];
        }, 'search');
    }

    /**
     * Full-text search dengan relevance scoring.
     * Auto-detect: MySQL (FULLTEXT) vs PostgreSQL (tsvector).
     */
    public function fullText(string $query): Builder
    {
        $driver = DB::getDriverName();
        $villageModel = app(config('wilayah.models.village'));

        if ($driver === 'pgsql') {
            return $villageModel::query()
                ->whereRaw(
                    "to_tsvector('simple', name) @@ plainto_tsquery('simple', ?)",
                    [$query]
                )
                ->orderByRaw(
                    "ts_rank(to_tsvector('simple', name), plainto_tsquery('simple', ?)) DESC",
                    [$query]
                );
        }

        // MySQL / MariaDB FULLTEXT
        return $villageModel::query()
            ->whereRaw('MATCH(name) AGAINST(? IN BOOLEAN MODE)', [$query.'*'])
            ->orderByRaw('MATCH(name) AGAINST(? IN BOOLEAN MODE) DESC', [$query.'*']);
    }

    /**
     * Pencarian alamat lengkap secara cascaded.
     * Input: string bebas → Output: hierarki terstruktur + confidence score.
     */
    public function searchAddress(string $address): array
    {
        $words = array_filter(explode(' ', $address));
        $result = [
            'province' => null,
            'regency' => null,
            'district' => null,
            'villages' => [],
            'confidence' => 0.0,
        ];

        $matched = 0;
        $total = 0;

        // Cari provinsi
        $total++;
        $province = app(config('wilayah.models.province'))::query()
            ->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('name', 'LIKE', '%'.$word.'%');
                }
            })
            ->first();

        if ($province) {
            $result['province'] = ['code' => $province->code, 'name' => $province->name];
            $matched++;

            // Cari regency di dalam provinsi ini
            $total++;
            $regency = app(config('wilayah.models.regency'))::query()
                ->where('province_id', $province->id)
                ->where(function ($q) use ($words) {
                    foreach ($words as $word) {
                        $q->orWhere('name', 'LIKE', '%'.$word.'%');
                    }
                })
                ->first();

            if ($regency) {
                $result['regency'] = ['code' => $regency->code, 'name' => $regency->name, 'type' => $regency->type];
                $matched++;

                // Cari district di dalam regency
                $total++;
                $district = app(config('wilayah.models.district'))::query()
                    ->where('regency_id', $regency->id)
                    ->where(function ($q) use ($words) {
                        foreach ($words as $word) {
                            $q->orWhere('name', 'LIKE', '%'.$word.'%');
                        }
                    })
                    ->first();

                if ($district) {
                    $result['district'] = ['code' => $district->code, 'name' => $district->name];
                    $matched++;

                    $result['villages'] = app(config('wilayah.models.village'))::query()
                        ->where('district_id', $district->id)
                        ->get(['code', 'name', 'type', 'postal_code'])
                        ->toArray();
                }
            }
        }

        $result['confidence'] = $total > 0 ? round($matched / $total, 2) : 0.0;

        return $result;
    }
}
