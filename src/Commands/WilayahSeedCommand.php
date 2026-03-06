<?php

namespace Aliziodev\Wilayah\Commands;

use Aliziodev\Wilayah\Database\Seeders\WilayahSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class WilayahSeedCommand extends Command
{
    protected $signature = 'wilayah:seed
                            {--fresh : Truncate semua tabel wilayah sebelum seeding}
                            {--with=* : Fitur opsional yang di-seed (islands, areas, populations)}
                            {--province= : Seed hanya untuk kode provinsi tertentu (contoh: 32)}';

    protected $description = 'Seed data wilayah Indonesia ke database.';

    public function handle(): int
    {
        $fresh = $this->option('fresh');
        $with = $this->option('with');
        $province = $this->option('province');

        if ($fresh && ! $this->confirm('⚠️  Data wilayah yang ada akan dihapus. Lanjutkan?')) {
            return self::FAILURE;
        }

        $this->info('🌱 Seeding data wilayah...');
        if ($province) {
            $this->comment("   Filter: hanya provinsi {$province}");
        }

        $start = microtime(true);

        app(WilayahSeeder::class)->run([
            'fresh' => $fresh,
            'with' => $with,
            'province' => $province,
        ]);

        Artisan::call('wilayah:cache-clear');

        $elapsed = round(microtime(true) - $start, 2);

        $this->newLine();
        $this->info("✅ Seeding selesai dalam {$elapsed} detik.");

        return self::SUCCESS;
    }
}
