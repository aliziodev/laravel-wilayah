<?php

namespace Aliziodev\Wilayah\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    public function run(array $params = []): void
    {
        $data = require __DIR__.'/../../../data/provinces.php';
        $table = config('wilayah.table_names.provinces', 'provinces');
        $chunk = config('wilayah.seeder.chunk_size', 500);
        $now = now()->toDateTimeString();

        $rows = array_map(fn ($row) => array_merge($row, [
            'created_at' => $now,
            'updated_at' => $now,
        ]), $data);

        // Upsert: insert baru, update nama jika kode sudah ada
        foreach (array_chunk($rows, $chunk) as $batch) {
            DB::table($table)->upsert(
                $batch,
                ['code'],
                ['name', 'updated_at']
            );
        }
    }
}
