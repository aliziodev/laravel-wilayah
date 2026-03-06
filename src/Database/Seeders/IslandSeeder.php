<?php

namespace Aliziodev\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IslandSeeder extends Seeder
{
    public function run(): void
    {
        if (! config('wilayah.features.islands')) {
            $this->command->warn('Fitur islands tidak aktif. Aktifkan via config wilayah.features.islands = true');

            return;
        }

        $file = __DIR__.'/../../../data/islands.php';
        if (! file_exists($file)) {
            return;
        }

        $data = require $file;
        $table = config('wilayah.table_names.islands', 'islands');
        $chunk = config('wilayah.seeder.chunk_size', 500);
        $now = now()->toDateTimeString();

        $regencyMap = DB::table(config('wilayah.table_names.regencies', 'regencies'))
            ->pluck('id', 'code')
            ->toArray();

        $rows = array_map(function ($row) use ($regencyMap, $now) {
            return [
                'code' => $row['code'],
                'regency_id' => $regencyMap[$row['regency_code'] ?? ''] ?? null,
                'name' => $row['name'],
                'lat' => $row['lat'] ?? null,
                'lng' => $row['lng'] ?? null,
                'is_named' => $row['is_named'] ?? true,
                'notes' => $row['notes'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $data);

        foreach (array_chunk($rows, $chunk) as $batch) {
            DB::table($table)->upsert($batch, ['code'], ['name', 'lat', 'lng', 'is_named', 'notes', 'updated_at']);
        }
    }
}
