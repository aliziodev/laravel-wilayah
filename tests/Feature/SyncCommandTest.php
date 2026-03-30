<?php

use Aliziodev\Wilayah\Database\Seeders\WilayahSeeder;
use Aliziodev\Wilayah\Tests\TestCase;

uses(TestCase::class);

test('sync command can run in dry-run mode', function () {
    $this->artisan('wilayah:sync', ['--dry-run' => true])
        ->expectsOutputToContain('Dry-run mode: tidak ada perubahan yang diterapkan')
        ->assertSuccessful();
});

test('sync command requires confirmation without dry-run', function () {
    $this->artisan('wilayah:sync')
        ->expectsConfirmation('▶ Terapkan perubahan sekarang?', 'no')
        ->assertFailed();
});

test('sync command runs seeder when confirmed', function () {
    // Mock the seeder so it doesn't actually insert data during test repeatedly
    $this->mock(WilayahSeeder::class, function ($mock) {
        $mock->shouldReceive('run')->once();
    });

    $this->artisan('wilayah:sync')
        ->expectsConfirmation('▶ Terapkan perubahan sekarang?', 'yes')
        ->expectsOutputToContain('Sync selesai')
        ->assertSuccessful();
});
