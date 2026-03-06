<?php

namespace Aliziodev\Wilayah\Services;

class DropdownService
{
    public function __construct(
        protected CacheService $cache
    ) {}

    /**
     * Format [code => name] untuk HTML select/dropdown.
     */
    public function forDropdown(
        string $level,
        ?string $province = null,
        ?string $regency = null,
        ?string $district = null,
        string $key = 'code'
    ): array {
        $cacheKey = "dropdown:{$level}:{$key}:".($province ?? '').':'.($regency ?? '').':'.($district ?? '');

        return $this->cache->remember($cacheKey, function () use ($level, $province, $regency, $district, $key) {
            $query = $this->buildQuery($level, $province, $regency, $district);

            return $query->pluck('name', $key)->toArray();
        });
    }

    /**
     * Format [{value, label}] untuk Livewire / Alpine.js.
     */
    public function forSelect(
        string $level,
        ?string $province = null,
        ?string $regency = null,
        ?string $district = null,
        string $key = 'code'
    ): array {
        $cacheKey = "select:{$level}:{$key}:".($province ?? '').':'.($regency ?? '').':'.($district ?? '');

        return $this->cache->remember($cacheKey, function () use ($level, $province, $regency, $district, $key) {
            $query = $this->buildQuery($level, $province, $regency, $district);

            return $query->get([$key, 'name'])
                ->map(fn ($item) => [
                    'value' => $item->{$key},
                    'label' => $item->name,
                ])
                ->toArray();
        });
    }

    protected function buildQuery(
        string $level,
        ?string $province,
        ?string $regency,
        ?string $district
    ) {
        return match ($level) {
            'provinces' => app(config('wilayah.models.province'))::query()
                ->orderBy('name'),

            'regencies' => app(config('wilayah.models.regency'))::query()
                ->when($province, function ($q) use ($province) {
                    $prov = app(config('wilayah.models.province'))::where('code', $province)->value('id');
                    $q->where('province_id', $prov);
                })
                ->orderBy('name'),

            'districts' => app(config('wilayah.models.district'))::query()
                ->when($regency, function ($q) use ($regency) {
                    $reg = app(config('wilayah.models.regency'))::where('code', $regency)->value('id');
                    $q->where('regency_id', $reg);
                })
                ->orderBy('name'),

            'villages' => app(config('wilayah.models.village'))::query()
                ->when($district, function ($q) use ($district) {
                    $dist = app(config('wilayah.models.district'))::where('code', $district)->value('id');
                    $q->where('district_id', $dist);
                })
                ->orderBy('name'),

            default => throw new \InvalidArgumentException(
                "Level '{$level}' tidak valid. Gunakan: provinces, regencies, districts, villages"
            ),
        };
    }
}
