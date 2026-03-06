<?php

namespace Aliziodev\Wilayah\Services;

use Illuminate\Database\Eloquent\Builder;

class SearchByPostalCodeService
{
    public function __construct(
        protected CacheService $cache
    ) {}

    /**
     * Query builder untuk pencarian by kode pos.
     * Mendukung exact match dan wildcard LIKE (misal: '401%').
     */
    public function search(string $postalCode): Builder
    {
        $villageModel = app(config('wilayah.models.village'));

        $postalCode = str_replace('*', '%', $postalCode);

        if (str_contains($postalCode, '%')) {
            // Wildcard search
            return $villageModel::query()
                ->where('postal_code', 'LIKE', $postalCode);
        }

        // Exact match
        return $villageModel::query()
            ->where('postal_code', $postalCode);
    }
}
