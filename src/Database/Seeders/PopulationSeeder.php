<?php

namespace Aliziodev\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulationSeeder extends Seeder
{
    public function run(array $params = []): void
    {
        if (! config('wilayah.features.populations')) {
            return;
        }

        $file = __DIR__.'/../../../data/populations.php';
        if (! file_exists($file)) {
            return;
        }

        $data = require $file;
        $table = config('wilayah.table_names.region_populations', 'region_populations');
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
                'male' => $row['male'] ?? 0,
                'female' => $row['female'] ?? 0,
                'total' => $row['total'] ?? 0,
                'year' => $row['year'] ?? 0,
            ];
        }, $data);

        $rows = array_filter($rows, fn ($r) => $r['model_id'] !== null);

        foreach (array_chunk($rows, $chunk) as $batch) {
            DB::table($table)->upsert(
                $batch,
                ['model_type', 'model_id', 'year'],
                ['male', 'female', 'total']
            );
        }
    }
}
