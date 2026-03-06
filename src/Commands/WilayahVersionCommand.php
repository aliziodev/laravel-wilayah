<?php

namespace Aliziodev\Wilayah\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class WilayahVersionCommand extends Command
{
    protected $signature = 'wilayah:version';

    protected $description = 'Tampilkan informasi versi data wilayah yang terpasang.';

    public function handle(): int
    {
        $versionFile = __DIR__.'/../../data/version.php';
        $pkg = file_exists($versionFile) ? require $versionFile : null;

        $this->info('🗺  Aliziodev Laravel Wilayah — Version Info');
        $this->line('══════════════════════════════════════════');

        if ($pkg) {
            $this->table(
                ['Info', 'Nilai'],
                [
                    ['Package version', 'v'.$pkg['version']],
                    ['Data date',       $pkg['data_date']],
                    ['Source hash',     substr($pkg['source_hash'] ?? 'N/A', 0, 8).'...'],
                ]
            );

            $this->newLine();
            $this->comment('Jumlah data di package:');
            $this->table(
                ['Level', 'Package', 'Di Database'],
                collect($pkg['counts'])->map(function ($count, $key) {
                    $db = DB::table(config("wilayah.table_names.{$key}", $key))->count();
                    $status = $count === $db ? '✅ Sync' : '⚠️  Perlu sync';

                    return [ucfirst($key), number_format($count), number_format($db)." ({$status})"];
                })->values()->toArray()
            );

            if ($pkg['counts']['provinces'] !== DB::table(config('wilayah.table_names.provinces', 'provinces'))->count()) {
                $this->newLine();
                $this->warn('Data berbeda dari package. Jalankan: php artisan wilayah:sync');
            }
        } else {
            $this->warn('Data version tidak ditemukan. Jalankan: php artisan wilayah:seed');
        }

        return self::SUCCESS;
    }
}
