<?php

namespace Aliziodev\Wilayah\Models;

use Aliziodev\Wilayah\Models\Concerns\HasArea;
use Aliziodev\Wilayah\Models\Concerns\HasPopulation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasArea, HasPopulation;

    protected $fillable = ['code', 'name'];

    public function getTable(): string
    {
        return config('wilayah.table_names.provinces', 'provinces');
    }

    public function regencies(): HasMany
    {
        return $this->hasMany(
            config('wilayah.models.regency'),
            'province_id'
        );
    }

    public function logoUrl(string $size = 'full'): ?string
    {
        $dir = $size === 'thumb' ? 'thumbs' : 'img';
        $path = "vendor/wilayah/logos/prov/{$dir}/{$this->code}.png";

        return file_exists(public_path($path)) ? asset($path) : null;
    }

    public function logoPath(string $size = 'full'): string
    {
        $dir = $size === 'thumb' ? 'thumbs' : 'img';

        return public_path("vendor/wilayah/logos/prov/{$dir}/{$this->code}.png");
    }
}
