<?php

namespace Aliziodev\Wilayah\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Database\Eloquent\Builder provinces()
 * @method static \Illuminate\Database\Eloquent\Builder regencies()
 * @method static \Illuminate\Database\Eloquent\Builder districts()
 * @method static \Illuminate\Database\Eloquent\Builder villages()
 * @method static mixed find(string $code)
 * @method static \Illuminate\Database\Eloquent\Builder findByCodePrefix(string $prefix)
 * @method static \Illuminate\Support\Collection search(string $query)
 * @method static \Illuminate\Database\Eloquent\Builder fullTextSearch(string $query)
 * @method static array searchAddress(string $address)
 * @method static \Illuminate\Database\Eloquent\Builder postalCode(string $code)
 * @method static \Illuminate\Database\Eloquent\Collection searchByPostalCode(string $code)
 * @method static \Aliziodev\Wilayah\Services\HierarchyService hierarchy(string $code)
 * @method static array ancestors(string $code)
 * @method static array forDropdown(string $level, ?string $province = null, ?string $regency = null, ?string $district = null, string $key = 'code')
 * @method static array forSelect(string $level, ?string $province = null, ?string $regency = null, ?string $district = null, string $key = 'code')
 * @method static void flushCache(?string $group = null)
 *
 * @see \Aliziodev\Wilayah\WilayahManager
 */
class Wilayah extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'wilayah';
    }
}
