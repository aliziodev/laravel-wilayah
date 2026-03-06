<?php

namespace Aliziodev\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegencySeeder extends Seeder
{
    public function run(array $params = []): void
    {
        $provinceFilter = $params['province'] ?? null;
        $data = require __DIR__.'/../../../data/regencies.php';
        $table = config('wilayah.table_names.regencies', 'regencies');
        $chunk = config('wilayah.seeder.chunk_size', 500);
        $now = now()->toDateTimeString();

        // Build province code → id map
        $provinceMap = DB::table(config('wilayah.table_names.provinces', 'provinces'))
            ->pluck('id', 'code')
            ->toArray();

        // Filter per provinsi jika ada
        if ($provinceFilter) {
            $data = array_filter($data, fn ($r) => str_starts_with($r['code'], $provinceFilter));
        }

        $rows = array_map(function ($row) use ($provinceMap, $now) {
            $provCode = substr($row['code'], 0, 2);

            return [
                'code' => $row['code'],
                'province_id' => $provinceMap[$provCode] ?? null,
                'name' => $row['name'],
                'type' => $row['type'] ?? 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, array_values($data));

        // Filter baris yang province_id-nya null (province belum ada di DB)
        $rows = array_filter($rows, fn ($r) => $r['province_id'] !== null);

        foreach (array_chunk($rows, $chunk) as $batch) {
            DB::table($table)->upsert(
                $batch,
                ['code'],
                ['name', 'type', 'updated_at']
            );
        }
    }
}
