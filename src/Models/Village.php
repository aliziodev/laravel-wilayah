<?php

namespace Aliziodev\Wilayah\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Village extends Model
{
    protected $fillable = ['code', 'district_id', 'name', 'type', 'postal_code'];

    public function getTable(): string
    {
        return config('wilayah.table_names.villages', 'villages');
    }

    /**
     * Tipe wilayah: desa atau kelurahan.
     */
    public function getTypeNameAttribute(): string
    {
        return $this->type === 1 ? 'Kelurahan' : 'Desa';
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(
            config('wilayah.models.district'),
            'district_id'
        );
    }
}
