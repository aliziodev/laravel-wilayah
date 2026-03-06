<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nama Tabel
    |--------------------------------------------------------------------------
    | Override nama tabel jika terjadi konflik dengan tabel existing di project.
    */
    'table_names' => [
        'provinces' => 'provinces',
        'regencies' => 'regencies',
        'districts' => 'districts',
        'villages' => 'villages',
        'islands' => 'islands',
        'region_areas' => 'region_areas',
        'region_populations' => 'region_populations',
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Classes
    |--------------------------------------------------------------------------
    | Override model jika Anda ingin extend atau menambah relasi custom.
    */
    'models' => [
        'province' => \Aliziodev\Wilayah\Models\Province::class,
        'regency' => \Aliziodev\Wilayah\Models\Regency::class,
        'district' => \Aliziodev\Wilayah\Models\District::class,
        'village' => \Aliziodev\Wilayah\Models\Village::class,
        'island' => \Aliziodev\Wilayah\Models\Island::class,
        'region_area' => \Aliziodev\Wilayah\Models\RegionArea::class,
        'region_population' => \Aliziodev\Wilayah\Models\RegionPopulation::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Fitur Opsional
    |--------------------------------------------------------------------------
    | Aktifkan fitur opsional. Migrasi tabel terkait hanya dijalankan
    | jika fitur yang bersangkutan aktif.
    */
    'features' => [
        'islands' => false,   // Aktifkan tabel & data pulau
        'areas' => false,   // Aktifkan data luas wilayah (km2)
        'populations' => false,   // Aktifkan data jumlah penduduk
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => true,
        'store' => null,       // null = gunakan default cache driver app
        'prefix' => 'wilayah',
        'ttl' => [
            'default' => 1440,  // menit — 24 jam untuk data statis
            'search' => 60,    // menit — 1 jam untuk hasil pencarian
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */
    'search' => [
        // Full-text search engine: 'like' | 'fulltext'
        // 'fulltext' membutuhkan FULLTEXT index (MySQL) atau tsvector (PostgreSQL)
        'driver' => 'like',

        // Minimum panjang karakter untuk pencarian
        'min_length' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Seeder Options
    |--------------------------------------------------------------------------
    */
    'seeder' => [
        // Jumlah baris per chunk saat seeding (untuk performa optimal)
        'chunk_size' => 500,
    ],

];
