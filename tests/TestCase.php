<?php

namespace Aliziodev\Wilayah\Tests;

use Aliziodev\Wilayah\WilayahServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            WilayahServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../src/Database/Migrations');
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Gunakan SQLite in-memory untuk kecepatan
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Matikan cache di test
        $app['config']->set('wilayah.cache.enabled', false);
    }

    /**
     * Seed data minimal untuk pengujian.
     */
    protected function seedTestData(): void
    {
        $provinces = [
            ['code' => '11', 'name' => 'ACEH'],
            ['code' => '32', 'name' => 'JAWA BARAT'],
            ['code' => '73', 'name' => 'KOTA MAKASSAR'],
        ];

        $regencies = [
            ['code' => '11.01', 'province_id' => 1, 'name' => 'KAB. SIMEULUE',  'type' => 0],
            ['code' => '32.73', 'province_id' => 2, 'name' => 'KOTA BANDUNG',   'type' => 1],
            ['code' => '32.01', 'province_id' => 2, 'name' => 'KAB. BOGOR',     'type' => 0],
        ];

        $districts = [
            ['code' => '11.01.01', 'regency_id' => 1, 'name' => 'TEUPAH SELATAN'],
            ['code' => '32.73.07', 'regency_id' => 2, 'name' => 'CICENDO'],
            ['code' => '32.73.08', 'regency_id' => 2, 'name' => 'ANDIR'],
        ];

        $villages = [
            ['code' => '11.01.01.2001', 'district_id' => 1, 'name' => 'LATIUNG',    'type' => 0, 'postal_code' => '23891'],
            ['code' => '32.73.07.1001', 'district_id' => 2, 'name' => 'ARJUNA',     'type' => 1, 'postal_code' => '40172'],
            ['code' => '32.73.07.1002', 'district_id' => 2, 'name' => 'HUSEIN SASTRANEGARA', 'type' => 1, 'postal_code' => '40174'],
            ['code' => '32.73.08.1001', 'district_id' => 3, 'name' => 'GARUDA',     'type' => 1, 'postal_code' => '40184'],
        ];

        $now = now()->toDateTimeString();

        \DB::table('provinces')->insert(array_map(fn ($r) => array_merge($r, ['created_at' => $now, 'updated_at' => $now]), $provinces));
        \DB::table('regencies')->insert(array_map(fn ($r) => array_merge($r, ['created_at' => $now, 'updated_at' => $now]), $regencies));
        \DB::table('districts')->insert(array_map(fn ($r) => array_merge($r, ['created_at' => $now, 'updated_at' => $now]), $districts));
        \DB::table('villages')->insert(array_map(fn ($r) => array_merge($r, ['created_at' => $now, 'updated_at' => $now]), $villages));
    }
}
