<?php

namespace Aliziodev\Wilayah\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model opsional — hanya aktif jika config('wilayah.features.areas') = true
 *
 * Menggunakan polymorphic relation sehingga bisa menampung luas
 * untuk Province maupun Regency.
 */
class RegionArea extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'model_type', 'model_id', 'area_km2',
    ];

    protected $casts = [
        'area_km2' => 'decimal:3',
    ];

    public function getTable(): string
    {
        return config('wilayah.table_names.region_areas', 'region_areas');
    }

    public function region()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
}
