<?php

uses(TestCase::class);
use Aliziodev\Wilayah\Services\CacheService;
use Aliziodev\Wilayah\Tests\TestCase;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->cacheManager = app('cache');
    $this->config = [
        'enabled' => true,
        'prefix' => 'wilayah_test',
        'ttl' => ['default' => 10, 'search' => 5],
        'store' => 'array',
    ];
    $this->cacheService = new CacheService($this->cacheManager, $this->config);
});

test('it checks if cache is enabled', function () {
    expect($this->cacheService->isEnabled())->toBeTrue();

    $disabledService = new CacheService($this->cacheManager, ['enabled' => false]);
    expect($disabledService->isEnabled())->toBeFalse();
});

test('it remembers a value in cache', function () {
    $value = $this->cacheService->remember('test_key', function () {
        return 'test_value';
    });

    expect($value)->toBe('test_value');

    // Check if it's actually cached in the array store
    $cachedValue = Cache::store('array')->get('wilayah_test:test_key');
    expect($cachedValue)->toBe('test_value');
});

test('it does not remember if disabled', function () {
    $disabledService = new CacheService($this->cacheManager, array_merge($this->config, ['enabled' => false]));

    $value = $disabledService->remember('disabled_key', function () {
        return 'fresh_value';
    });

    expect($value)->toBe('fresh_value');
    expect(Cache::store('array')->has('wilayah_test:disabled_key'))->toBeFalse();
});

test('it flushes specific group pattern', function () {
    $this->cacheService->remember('group:*', fn () => 'val1');

    $this->cacheService->flush('group');

    expect(Cache::store('array')->has('wilayah_test:group:*'))->toBeFalse();
});
