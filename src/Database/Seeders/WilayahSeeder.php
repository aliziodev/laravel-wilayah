<?php

namespace Aliziodev\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        // Seed 4 level inti selalu
        $this->call(ProvinceSeeder::class, false, ['province' => $province]);
        $this->call(RegencySeeder::class, false, ['province' => $province]);
        $this->call(DistrictSeeder::class, false, ['province' => $province]);
        $this->call(VillageSeeder::class, false, ['province' => $province]);

        // Seed opsional hanya jika diminta
        if (in_array('islands', $withFeatures) && config('wilayah.features.islands')) {
            $this->call(IslandSeeder::class);
        }

        if (in_array('areas', $withFeatures) && config('wilayah.features.areas')) {
            $this->call(AreaSeeder::class, false, ['province' => $province]);
        }

        if (in_array('populations', $withFeatures) && config('wilayah.features.populations')) {
            $this->call(PopulationSeeder::class, false, ['province' => $province]);
        }
    }

    protected function truncateTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

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

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
