<?php

namespace Aliziodev\Wilayah\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model opsional — hanya aktif jika config('wilayah.features.populations') = true
 *
 * Menggunakan polymorphic relation sehingga bisa menampung data penduduk
 * untuk Province maupun Regency.
 */
class RegionPopulation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'model_type', 'model_id',
        'male', 'female', 'total', 'year',
    ];

    protected $casts = [
        'male' => 'integer',
        'female' => 'integer',
        'total' => 'integer',
        'year' => 'integer',
    ];

    public function getTable(): string
    {
        return config('wilayah.table_names.region_populations', 'region_populations');
    }

    public function region()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
}
