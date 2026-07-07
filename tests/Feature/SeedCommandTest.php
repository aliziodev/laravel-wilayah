<?php

use Aliziodev\Wilayah\Database\Seeders\WilayahSeeder;
use Illuminate\Support\Facades\DB;

// Regression: parameter seeder anak harus dikirim dengan kunci 'params',
// jika tidak, spread named-arguments memicu "Unknown named parameter $province".
test('seed command runs with province filter without named parameter error', function () {
    $this->artisan('wilayah:seed', ['--province' => '11'])
        ->assertSuccessful();

    expect(DB::table('provinces')->where('code', '11')->exists())->toBeTrue()
        ->and(DB::table('provinces')->count())->toBe(1)
        ->and(DB::table('regencies')->count())->toBeGreaterThan(0)
        ->and(DB::table('regencies')->where('code', 'not like', '11.%')->count())->toBe(0)
        ->and(DB::table('districts')->count())->toBeGreaterThan(0)
        ->and(DB::table('villages')->count())->toBeGreaterThan(0);
});

// Jalur container (seperti db:seed / DatabaseSeeder): filter harus tetap sampai ke seeder anak.
test('wilayah seeder delivers the province filter through the container path', function () {
    $seeder = app(WilayahSeeder::class);
    $seeder->setContainer(app());

    $seeder->__invoke(['options' => ['province' => '11']]);

    expect(DB::table('provinces')->count())->toBe(1)
        ->and(DB::table('villages')->count())->toBeGreaterThan(0);
});
