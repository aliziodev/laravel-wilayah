<?php

namespace Aliziodev\Wilayah\Models;

use Aliziodev\Wilayah\Models\Concerns\HasArea;
use Aliziodev\Wilayah\Models\Concerns\HasPopulation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Regency extends Model
{
    use HasArea, HasPopulation;

    protected $fillable = ['code', 'province_id', 'name', 'type'];

    public function getTable(): string
    {
        return config('wilayah.table_names.regencies', 'regencies');
    }

    /**
     * Tipe wilayah: kabupaten atau kota.
     */
    public function getTypeNameAttribute(): string
    {
        return $this->type === 1 ? 'Kota' : 'Kabupaten';
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(
            config('wilayah.models.province'),
            'province_id'
        );
    }

    public function districts(): HasMany
    {
        return $this->hasMany(
            config('wilayah.models.district'),
            'regency_id'
        );
    }

    public function logoUrl(string $size = 'full'): ?string
    {
        $dir = $size === 'thumb' ? 'thumbs' : 'img';
        $provCode = substr($this->code, 0, 2);
        $path = "vendor/wilayah/logos/kab/{$provCode}/{$dir}/{$this->code}.png";

        return file_exists(public_path($path)) ? asset($path) : null;
    }

    public function logoPath(string $size = 'full'): string
    {
        $dir = $size === 'thumb' ? 'thumbs' : 'img';
        $provCode = substr($this->code, 0, 2);

        return public_path("vendor/wilayah/logos/kab/{$provCode}/{$dir}/{$this->code}.png");
    }
}
