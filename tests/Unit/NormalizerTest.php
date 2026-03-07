<?php

uses(\Aliziodev\Wilayah\Tests\TestCase::class);

test('normalize script exists', function () {
    $scriptPath = dirname(__DIR__, 2).'/.github/scripts/normalize.php';
    expect(file_exists($scriptPath))->toBeTrue();
});

test('normalize script has no syntax errors', function () {
    $scriptPath = dirname(__DIR__, 2).'/.github/scripts/normalize.php';
    exec('php -l '.escapeshellarg($scriptPath), $output, $returnVar);

    expect($returnVar)->toBe(0)
        ->and(implode("\n", $output))->toContain('No syntax errors detected');
});
