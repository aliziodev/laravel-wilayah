<?php

namespace Aliziodev\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WilayahSeeder extends Seeder
{
    public function run(array $options = []): void
    {
        $withFeatures = $options['with'] ?? [];
        $province = $options['province'] ?? null;
        $fresh = $options['fresh'] ?? false;

        // Disable query log untuk performa
        DB::disableQueryLog();

        if ($fresh) {
            $this->truncateTables();
        }

        // Kunci 'params' WAJIB sama dengan nama argumen run(array $params) di seeder anak.
        // Tanpa container, Seeder::__invoke men-spread array ini sebagai named arguments (PHP 8),
        // sehingga kunci lain memicu "Unknown named parameter".
        $parameters = ['params' => ['province' => $province]];

        // Seed 4 level inti selalu
        $this->call(ProvinceSeeder::class, false, $parameters);
        $this->call(RegencySeeder::class, false, $parameters);
        $this->call(DistrictSeeder::class, false, $parameters);
        $this->call(VillageSeeder::class, false, $parameters);

        // Seed opsional hanya jika diminta
        if (in_array('islands', $withFeatures) && config('wilayah.features.islands')) {
            $this->call(IslandSeeder::class);
        }

        if (in_array('areas', $withFeatures) && config('wilayah.features.areas')) {
            $this->call(AreaSeeder::class, false, $parameters);
        }

        if (in_array('populations', $withFeatures) && config('wilayah.features.populations')) {
            $this->call(PopulationSeeder::class, false, $parameters);
        }
    }

    protected function truncateTables(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table(config('wilayah.table_names.villages', 'villages'))->truncate();
        DB::table(config('wilayah.table_names.districts', 'districts'))->truncate();
        DB::table(config('wilayah.table_names.regencies', 'regencies'))->truncate();
        DB::table(config('wilayah.table_names.provinces', 'provinces'))->truncate();

        if (config('wilayah.features.islands')) {
            DB::table(config('wilayah.table_names.islands', 'islands'))->truncate();
        }
        if (config('wilayah.features.areas')) {
            DB::table(config('wilayah.table_names.region_areas', 'region_areas'))->truncate();
        }
        if (config('wilayah.features.populations')) {
            DB::table(config('wilayah.table_names.region_populations', 'region_populations'))->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }
}
