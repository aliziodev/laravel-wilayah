<?php

namespace Aliziodev\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    public function run(array $params = []): void
    {
        if (! config('wilayah.features.areas')) {
            return;
        }

        $file = __DIR__.'/../../../data/areas.php';
        if (! file_exists($file)) {
            return;
        }

        $data = require $file;
        $table = config('wilayah.table_names.region_areas', 'region_areas');
        $chunk = config('wilayah.seeder.chunk_size', 500);

        $provinceMap = DB::table(config('wilayah.table_names.provinces', 'provinces'))
            ->pluck('id', 'code')
            ->toArray();
        $regencyMap = DB::table(config('wilayah.table_names.regencies', 'regencies'))
            ->pluck('id', 'code')
            ->toArray();

        $rows = array_map(function ($row) use ($provinceMap, $regencyMap) {
            $id = strlen($row['code']) === 2
                ? ($provinceMap[$row['code']] ?? null)
                : ($regencyMap[$row['code']] ?? null);
            $type = strlen($row['code']) === 2 ? 'province' : 'regency';

            return [
                'model_type' => $type,
                'model_id' => $id,
                'area_km2' => $row['area_km2'],
            ];
        }, $data);

        $rows = array_filter($rows, fn ($r) => $r['model_id'] !== null);

        foreach (array_chunk($rows, $chunk) as $batch) {
            DB::table($table)->upsert($batch, ['model_type', 'model_id'], ['area_km2']);
        }
    }
}
