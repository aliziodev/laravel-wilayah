<?php

namespace Aliziodev\Wilayah\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class WilayahInstallCommand extends Command
{
    protected $signature = 'wilayah:install
                            {--no-seed : Jangan jalankan seeder setelah install}
                            {--fresh : Truncate tabel sebelum seeding}';

    protected $description = 'Install package wilayah: publish config, migrate, dan seed data.';

    public function handle(): int
    {
        $this->info('🗺  Aliziodev Laravel Wilayah — Installer');
        $this->newLine();

        // 1. Publish config
        $this->comment('📄 Publishing config...');
        Artisan::call('vendor:publish', [
            '--tag' => 'wilayah-config',
            '--provider' => 'Aliziodev\\Wilayah\\WilayahServiceProvider',
        ]);
        $this->info('   Config published ke config/wilayah.php');

        // 2. Publish migrations
        $this->comment('📋 Publishing migrations...');
        Artisan::call('vendor:publish', [
            '--tag' => 'wilayah-migrations',
            '--provider' => 'Aliziodev\\Wilayah\\WilayahServiceProvider',
        ]);
        $this->info('   Migrations published ke database/migrations/');

        // 3. Migrate
        $this->comment('🔧 Menjalankan migrasi...');
        if ($this->confirm('Jalankan php artisan migrate sekarang?', true)) {
            Artisan::call('migrate');
            $this->info(Artisan::output());
        }

        // 4. Seed
        if (! $this->option('no-seed')) {
            $this->comment('🌱 Seeding data wilayah...');
            $args = ['--fresh' => $this->option('fresh')];
            Artisan::call('wilayah:seed', $args);
            $this->info(Artisan::output());
        }

        $this->newLine();
        $this->info('✅ Instalasi selesai!');
        $this->table(
            ['Command', 'Keterangan'],
            [
                ['php artisan wilayah:sync', 'Sinkronisasi data terbaru dari upstream'],
                ['php artisan wilayah:version', 'Cek versi data yang terpasang'],
                ['php artisan wilayah:cache-clear', 'Bersihkan cache wilayah'],
            ]
        );

        return self::SUCCESS;
    }
}
