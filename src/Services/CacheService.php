<?php

namespace Aliziodev\Wilayah\Services;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;

class CacheService
{
    protected Repository $store;

    protected string $prefix;

    protected array $ttl;

    protected bool $enabled;

    public function __construct(
        CacheManager $cache,
        array $config
    ) {
        $this->enabled = $config['enabled'] ?? true;
        $this->prefix = $config['prefix'] ?? 'wilayah';
        $this->ttl = $config['ttl'] ?? ['default' => 1440, 'search' => 60];
        $this->store = $cache->store($config['store'] ?? null);
    }

    public function remember(string $key, callable $callback, string $ttlKey = 'default'): mixed
    {
        if (! $this->enabled) {
            return $callback();
        }

        $ttlMinutes = $this->ttl[$ttlKey] ?? $this->ttl['default'];

        return $this->store->remember(
            $this->key($key),
            now()->addMinutes($ttlMinutes),
            $callback
        );
    }

    public function flush(?string $group = null): void
    {
        if ($group) {
            $this->store->forget($this->key($group.':*'));
        } else {
            // Flush semua dengan prefix wilayah
            // Untuk driver yang support tags
            if (method_exists($this->store->getStore(), 'tags')) {
                $this->store->tags($this->prefix)->flush();
            } else {
                // Fallback: flush seluruh cache
                $this->store->flush();
            }
        }
    }

    protected function key(string $key): string
    {
        return $this->prefix.':'.$key;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
