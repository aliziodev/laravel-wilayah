<?php

namespace Aliziodev\Wilayah;

use Aliziodev\Wilayah\Services\CacheService;
use Aliziodev\Wilayah\Services\DropdownService;
use Aliziodev\Wilayah\Services\HierarchyService;
use Aliziodev\Wilayah\Services\SearchByPostalCodeService;
use Aliziodev\Wilayah\Services\SearchService;
use Illuminate\Database\Eloquent\Builder;

class WilayahManager
{
    public function __construct(
        protected SearchService $search,
        protected SearchByPostalCodeService $postalCodeSearch,
        protected HierarchyService $hierarchy,
        protected DropdownService $dropdown,
        protected CacheService $cache,
    ) {}

    // ───────────────────────────────────────────────
    // Model Query Builders
    // ───────────────────────────────────────────────

    public function provinces(): Builder
    {
        return app(config('wilayah.models.province'))::query();
    }

    public function regencies(): Builder
    {
        return app(config('wilayah.models.regency'))::query();
    }

    public function districts(): Builder
    {
        return app(config('wilayah.models.district'))::query();
    }

    public function villages(): Builder
    {
        return app(config('wilayah.models.village'))::query();
    }

    // ───────────────────────────────────────────────
    // Find by Code
    // ───────────────────────────────────────────────

    /**
     * Cari wilayah berdasarkan kode wilayah di semua level.
     * Mendeteksi level secara otomatis dari panjang kode.
     */
    public function find(string $code): mixed
    {
        return match (strlen($code)) {
            2 => $this->provinces()->where('code', $code)->first(),
            5 => $this->regencies()->where('code', $code)->first(),
            8 => $this->districts()->where('code', $code)->first(),
            default => $this->villages()->where('code', $code)->first(),
        };
    }

    /**
     * Cari semua wilayah yang kodenya diawali dengan prefix tertentu.
     */
    public function findByCodePrefix(string $prefix): Builder
    {
        return match (strlen($prefix)) {
            2 => $this->regencies()->where('code', 'LIKE', $prefix.'%'),
            5 => $this->districts()->where('code', 'LIKE', $prefix.'%'),
            8 => $this->villages()->where('code', 'LIKE', $prefix.'%'),
            default => $this->provinces()->where('code', 'LIKE', $prefix.'%'),
        };
    }

    // ───────────────────────────────────────────────
    // Search
    // ───────────────────────────────────────────────

    /**
     * Pencarian sederhana berdasarkan nama di semua level.
     */
    public function search(string $query): array
    {
        return $this->search->searchAll($query);
    }

    /**
     * Full-text search dengan relevance scoring.
     * MySQL: FULLTEXT index | PostgreSQL: tsvector + GIN index
     */
    public function fullTextSearch(string $query): Builder
    {
        return $this->search->fullText($query);
    }

    /**
     * Pencarian alamat lengkap (cascaded), mengembalikan hierarki+confidence score.
     */
    public function searchAddress(string $address): array
    {
        return $this->search->searchAddress($address);
    }

    // ───────────────────────────────────────────────
    // Postal Code
    // ───────────────────────────────────────────────

    /**
     * Cari desa/kelurahan berdasarkan kode pos.
     * Return Collection karena 1 kode pos bisa = banyak desa.
     */
    public function postalCode(string $code): Builder
    {
        return $this->postalCodeSearch->search($code);
    }

    public function searchByPostalCode(string $code): \Illuminate\Database\Eloquent\Collection
    {
        return $this->postalCodeSearch->search($code)->with([
            'district.regency.province',
        ])->get();
    }

    // ───────────────────────────────────────────────
    // Hierarchy
    // ───────────────────────────────────────────────

    /**
     * Kembalikan hierarki lengkap dari kode wilayah apapun.
     */
    public function hierarchy(string $code): HierarchyService
    {
        return $this->hierarchy->load($code);
    }

    /**
     * Kembalikan array ancestors (dari level tertinggi ke code yang diberikan).
     */
    public function ancestors(string $code): array
    {
        return $this->hierarchy->ancestors($code);
    }

    // ───────────────────────────────────────────────
    // Dropdown / Select
    // ───────────────────────────────────────────────

    /**
     * Format [code => name] untuk HTML select/dropdown.
     *
     * @param  string  $level  'provinces'|'regencies'|'districts'|'villages'
     * @param  string|null  $province  Kode provinsi (filter)
     * @param  string|null  $regency  Kode kab/kota (filter)
     * @param  string|null  $district  Kode kecamatan (filter)
     * @param  string  $key  'code'|'id'
     */
    public function forDropdown(
        string $level,
        ?string $province = null,
        ?string $regency = null,
        ?string $district = null,
        string $key = 'code'
    ): array {
        return $this->dropdown->forDropdown($level, $province, $regency, $district, $key);
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
        return $this->dropdown->forSelect($level, $province, $regency, $district, $key);
    }

    // ───────────────────────────────────────────────
    // Cache
    // ───────────────────────────────────────────────

    public function flushCache(?string $group = null): void
    {
        $this->cache->flush($group);
    }
}
