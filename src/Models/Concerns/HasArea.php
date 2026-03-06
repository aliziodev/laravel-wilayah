<?php

namespace Aliziodev\Wilayah\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Trait untuk menambahkan relasi data luas wilayah ke model.
 * Hanya aktif jika config('wilayah.features.areas') = true.
 */
trait HasArea
{
    public function area(): MorphOne
    {
        return $this->morphOne(
            config('wilayah.models.region_area'),
            'region',
            'model_type',
            'model_id'
        );
    }

    /**
     * Helper: langsung ambil luas dalam km2.
     */
    public function getAreaKm2Attribute(): ?float
    {
        if (! config('wilayah.features.areas')) {
            return null;
        }

        return $this->area?->area_km2;
    }
}
