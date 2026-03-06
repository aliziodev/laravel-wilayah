<?php

namespace Aliziodev\Wilayah\Services;

class HierarchyService
{
    protected ?array $loaded = null;

    public function __construct(
        protected CacheService $cache
    ) {}

    /**
     * Load hierarki dari kode wilayah apapun (prov/kab/kec/desa).
     * Mendeteksi level secara otomatis dari panjang kode.
     */
    public function load(string $code): static
    {
        if (! preg_match('/^\d{2}(\.\d{2}(\.\d{2}(\.\d{4})?)?)?$/', $code)) {
            throw new \InvalidArgumentException('Format kode wilayah tidak valid.');
        }

        $cacheKey = 'hierarchy:'.$code;

        $this->loaded = $this->cache->remember($cacheKey, function () use ($code) {
            $len = strlen($code);

            if ($len >= 13) {
                // Level desa
                $village = app(config('wilayah.models.village'))::where('code', $code)->first();
                $district = $village ? app(config('wilayah.models.district'))::find($village->district_id) : null;
                $regency = $district ? app(config('wilayah.models.regency'))::find($district->regency_id) : null;
                $province = $regency ? app(config('wilayah.models.province'))::find($regency->province_id) : null;

                return compact('province', 'regency', 'district', 'village');
            }

            if ($len === 8) {
                // Level kecamatan
                $district = app(config('wilayah.models.district'))::where('code', $code)->first();
                $regency = $district ? app(config('wilayah.models.regency'))::find($district->regency_id) : null;
                $province = $regency ? app(config('wilayah.models.province'))::find($regency->province_id) : null;

                return ['province' => $province, 'regency' => $regency, 'district' => $district, 'village' => null];
            }

            if ($len === 5) {
                // Level kabupaten/kota
                $regency = app(config('wilayah.models.regency'))::where('code', $code)->first();
                $province = $regency ? app(config('wilayah.models.province'))::find($regency->province_id) : null;

                return ['province' => $province, 'regency' => $regency, 'district' => null, 'village' => null];
            }

            // Level provinsi
            $province = app(config('wilayah.models.province'))::where('code', $code)->first();

            return ['province' => $province, 'regency' => null, 'district' => null, 'village' => null];
        });

        if (empty(array_filter($this->loaded))) {
            throw new \InvalidArgumentException('Kode wilayah tidak valid atau tidak ditemukan.');
        }

        return $this;
    }

    /**
     * Format: "Kel. Arjuna, Kec. Cicendo, Kota Bandung, Jawa Barat 40172"
     */
    public function toAddress(): string
    {
        $parts = [];

        if ($v = $this->loaded['village'] ?? null) {
            $prefix = $v->type === 1 ? 'Kel.' : 'Desa';
            $parts[] = "{$prefix} {$v->name}";
        }

        if ($d = $this->loaded['district'] ?? null) {
            $parts[] = "Kec. {$d->name}";
        }

        if ($r = $this->loaded['regency'] ?? null) {
            $prefix = $r->type === 1 ? 'Kota' : 'Kab.';
            $parts[] = "{$prefix} {$r->name}";
        }

        if ($p = $this->loaded['province'] ?? null) {
            $suffix = '';
            if (($v = $this->loaded['village'] ?? null) && $v->postal_code) {
                $suffix = ' '.$v->postal_code;
            }
            $parts[] = $p->name.$suffix;
        }

        return implode(', ', $parts);
    }

    /**
     * Format singkat: "Cicendo, Kota Bandung, Jawa Barat"
     */
    public function toShortAddress(): string
    {
        $parts = [];

        if ($d = $this->loaded['district'] ?? null) {
            $parts[] = $d->name;
        }

        if ($r = $this->loaded['regency'] ?? null) {
            $prefix = $r->type === 1 ? 'Kota' : 'Kab.';
            $parts[] = "{$prefix} {$r->name}";
        }

        if ($p = $this->loaded['province'] ?? null) {
            $parts[] = $p->name;
        }

        return implode(', ', $parts);
    }

    /**
     * Magic method untuk mengambil property hierarki dengan mudah.
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Kembalikan array ancestors dari level tertinggi ke level kode yang diberikan.
     */
    public function ancestors(?string $code = null): array
    {
        if ($code !== null) {
            $this->load($code);
        }

        return array_filter([
            'province' => $this->loaded['province'] ?? null,
            'regency' => $this->loaded['regency'] ?? null,
            'district' => $this->loaded['district'] ?? null,
            'village' => $this->loaded['village'] ?? null,
        ]);
    }

    /**
     * Ambil item hierarki spesifik.
     */
    public function get(string $level): mixed
    {
        return $this->loaded[$level] ?? null;
    }

    /**
     * Konversi ke array.
     */
    public function toArray(): array
    {
        return $this->loaded ?? [];
    }
}
