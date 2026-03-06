<?php

namespace Aliziodev\Wilayah\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Trait untuk menambahkan relasi data penduduk ke model.
 * Hanya aktif jika config('wilayah.features.populations') = true.
 */
trait HasPopulation
{
    public function population(): MorphOne
    {
        return $this->morphOne(
            config('wilayah.models.region_population'),
            'region',
            'model_type',
            'model_id'
        );
    }

    /**
     * Helper: langsung ambil total penduduk.
     */
    public function getTotalPopulationAttribute(): ?int
    {
        if (! config('wilayah.features.populations')) {
            return null;
        }

        return $this->population?->total;
    }
}
