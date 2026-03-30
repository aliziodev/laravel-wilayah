<?php

namespace Aliziodev\Wilayah\Commands;

use Aliziodev\Wilayah\Database\Seeders\WilayahSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class WilayahSyncCommand extends Command
{
    protected $signature = 'wilayah:sync
                            {--dry-run : Preview perubahan tanpa menerapkan}
                            {--province= : Sync hanya untuk kode provinsi tertentu}
                            {--diff : Tampilkan changelog lengkap}';

    protected $description = 'Sinkronisasi data wilayah terbaru dari package (Upsert — aman, tidak menghapus data).';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $province = $this->option('province');
        $diff = $this->option('diff');

        $versionFile = __DIR__.'/../../data/version.php';
        $pkgVersion = file_exists($versionFile) ? require $versionFile : [];

        $dbVersion = $this->getDbVersion();

        $this->info('🔄 Wilayah Data Sync');
        $this->line('══════════════════════════════════');
        $this->line("Package : v{$pkgVersion['version']} ({$pkgVersion['data_date']})");
        $this->line("Database: v{$dbVersion['version']} ({$dbVersion['data_date']})");
        $this->newLine();

        // Hitung perbedaan jumlah data
        $counts = $this->countDiff($pkgVersion, $dbVersion);
        $this->table(['Level', 'Database', 'Package', 'Perubahan'], $counts['table']);

        if ($diff) {
            $this->showDiff($pkgVersion, $dbVersion);
        }

        if ($dryRun) {
            $this->newLine();
            $this->comment('ℹ️  Dry-run mode: tidak ada perubahan yang diterapkan.');
            $this->comment('   Jalankan tanpa --dry-run untuk sinkronisasi.');

            return self::SUCCESS;
        }

        if (! $this->confirm('▶ Terapkan perubahan sekarang?', true)) {
            return self::FAILURE;
        }

        $this->info('Menjalankan seeder (upsert mode)...');

        app(WilayahSeeder::class)->run(['province' => $province]);

        Artisan::call('wilayah:cache-clear');

        $this->info('✅ Sync selesai! Data wilayah sudah diperbarui.');

        return self::SUCCESS;
    }

    protected function getDbVersion(): array
    {
        // Cek versi yang sudah ada di DB (bisa disimpan di tabel khusus atau cache)
        return [
            'version' => config('wilayah.installed_version', '0.0.0'),
            'data_date' => config('wilayah.installed_date', 'Belum terpasang'),
        ];
    }

    protected function countDiff(array $pkgVersion, array $dbVersion): array
    {
        $levels = [
            ['Provinces', 'provinces'],
            ['Regencies', 'regencies'],
            ['Districts', 'districts'],
            ['Villages',  'villages'],
        ];

        $table = [];
        foreach ($levels as [$label, $key]) {
            $pkg = $pkgVersion['counts'][$key] ?? 0;
            $db = DB::table(config("wilayah.table_names.{$key}", $key))->count();
            $diff = $pkg - $db;
            $table[] = [$label, $db, $pkg, $diff >= 0 ? "+{$diff} baru" : "{$diff}"];
        }

        return ['table' => $table];
    }

    protected function showDiff(array $pkgVersion, array $dbVersion): void
    {
        $this->comment('Changelog lengkap tidak tersedia dalam mode ini.');
        $this->comment('Jalankan wilayah:sync untuk melihat perubahan setelah sync.');
    }
}
