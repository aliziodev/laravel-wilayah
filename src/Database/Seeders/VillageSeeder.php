<?php

namespace Aliziodev\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillageSeeder extends Seeder
{
    public function run(array $params = []): void
    {
        $provinceFilter = $params['province'] ?? null;
        $table = config('wilayah.table_names.villages', 'villages');
        $chunk = config('wilayah.seeder.chunk_size', 500);
        $now = now()->toDateTimeString();
        $dataDir = __DIR__.'/../../../data/villages';

        // Build district code → id map (bisa besar, load sekali)
        $districtMap = DB::table(config('wilayah.table_names.districts', 'districts'))
            ->pluck('id', 'code')
            ->toArray();

        $files = glob($dataDir.'/villages_*.php');

        foreach ($files as $file) {
            $provCode = preg_replace('/.*villages_(\d+)\.php$/', '$1', $file);

            if ($provinceFilter && $provCode !== $provinceFilter) {
                continue;
            }

            $data = require $file;

            $rows = array_map(function ($row) use ($districtMap, $now) {
                $distCode = substr($row['code'], 0, 8);

                return [
                    'code' => $row['code'],
                    'district_id' => $districtMap[$distCode] ?? null,
                    'name' => $row['name'],
                    'type' => $row['type'] ?? 0,
                    'postal_code' => $row['postal_code'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $data);

            $rows = array_filter($rows, fn ($r) => $r['district_id'] !== null);

            foreach (array_chunk($rows, $chunk) as $batch) {
                DB::table($table)->upsert(
                    $batch,
                    ['code'],
                    ['name', 'type', 'postal_code', 'updated_at']
                );
            }

            // Bebaskan memori setelah tiap file (file village besar)
            unset($data, $rows);
            gc_collect_cycles();
        }
    }
}
