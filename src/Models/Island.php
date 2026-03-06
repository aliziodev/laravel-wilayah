<?php

namespace Aliziodev\Wilayah\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model opsional — hanya aktif jika config('wilayah.features.islands') = true
 */
class Island extends Model
{
    protected $fillable = [
        'code', 'regency_id', 'name',
        'lat', 'lng', 'is_named', 'notes',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'is_named' => 'boolean',
    ];

    public function getTable(): string
    {
        return config('wilayah.table_names.islands', 'islands');
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(
            config('wilayah.models.regency'),
            'regency_id'
        );
    }
}
