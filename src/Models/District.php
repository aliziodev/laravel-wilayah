<?php

namespace Aliziodev\Wilayah\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $fillable = ['code', 'regency_id', 'name'];

    public function getTable(): string
    {
        return config('wilayah.table_names.districts', 'districts');
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(
            config('wilayah.models.regency'),
            'regency_id'
        );
    }

    public function villages(): HasMany
    {
        return $this->hasMany(
            config('wilayah.models.village'),
            'district_id'
        );
    }
}
