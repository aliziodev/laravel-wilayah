<?php

namespace Aliziodev\Wilayah\Commands;

use Aliziodev\Wilayah\Services\CacheService;
use Illuminate\Console\Command;

class WilayahCacheClearCommand extends Command
{
    protected $signature = 'wilayah:cache-clear
                             {group? : Grup cache yang dihapus (provinces, regencies, dll)}';

    protected $description = 'Bersihkan cache data wilayah.';

    public function handle(CacheService $cache): int
    {
        $group = $this->argument('group');

        $cache->flush($group);

        if ($group) {
            $this->info("✅ Cache wilayah grup '{$group}' berhasil dibersihkan.");
        } else {
            $this->info('✅ Seluruh cache wilayah berhasil dibersihkan.');
        }

        return self::SUCCESS;
    }
}
