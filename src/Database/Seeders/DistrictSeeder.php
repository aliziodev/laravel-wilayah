<?php

namespace Aliziodev\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    public function run(array $params = []): void
    {
        $provinceFilter = $params['province'] ?? null;
        $table = config('wilayah.table_names.districts', 'districts');
        $chunk = config('wilayah.seeder.chunk_size', 500);
        $now = now()->toDateTimeString();
        $dataDir = __DIR__.'/../../../data/districts';

        // Build regency code → id map
        $regencyMap = DB::table(config('wilayah.table_names.regencies', 'regencies'))
            ->pluck('id', 'code')
            ->toArray();

        // Scan file per provinsi di direktori data/districts/
        $files = glob($dataDir.'/districts_*.php');

        foreach ($files as $file) {
            // Ambil kode provinsi dari nama file (misal: districts_11.php → '11')
            $provCode = preg_replace('/.*districts_(\d+)\.php$/', '$1', $file);

            if ($provinceFilter && $provCode !== $provinceFilter) {
                continue;
            }

            $data = require $file;

            $rows = array_map(function ($row) use ($regencyMap, $now) {
                $regCode = substr($row['code'], 0, 5);

                return [
                    'code' => $row['code'],
                    'regency_id' => $regencyMap[$regCode] ?? null,
                    'name' => $row['name'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $data);

            $rows = array_filter($rows, fn ($r) => $r['regency_id'] !== null);

            foreach (array_chunk($rows, $chunk) as $batch) {
                DB::table($table)->upsert(
                    $batch,
                    ['code'],
                    ['name', 'updated_at']
                );
            }
        }
    }
}
