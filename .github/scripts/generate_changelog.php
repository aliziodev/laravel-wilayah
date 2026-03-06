<?php

declare(strict_types=1);

if ($argc < 7) {
    fwrite(STDERR, "Usage: php generate_changelog.php <previous-version.php> <current-version.php> <changelog.md> <release-notes.md> <previous-package-version> <new-package-version>\n");
    exit(1);
}

[$script, $previousVersionPath, $currentVersionPath, $changelogPath, $releaseNotesPath, $previousPackageVersion, $newPackageVersion] = $argv;

$previous = loadVersionFile($previousVersionPath);
$current = loadVersionFile($currentVersionPath);

$releaseDate = $current['data_date'] ?? gmdate('Y-m-d');
$countLabels = [
    'provinces' => 'Provinces',
    'regencies' => 'Regencies',
    'districts' => 'Districts',
    'villages' => 'Villages',
];

$statsLines = [];
foreach ($countLabels as $key => $label) {
    $old = (int) ($previous['counts'][$key] ?? 0);
    $new = (int) ($current['counts'][$key] ?? 0);
    $delta = $new - $old;
    $statsLines[] = sprintf('- %s: %d -> %d (%s)', $label, $old, $new, formatDelta($delta));
}

$oldWilayahHash = shortHash($previous['sources']['wilayah']['hash'] ?? $previous['source_hash'] ?? 'unknown');
$newWilayahHash = shortHash($current['sources']['wilayah']['hash'] ?? $current['source_hash'] ?? 'unknown');
$oldKodeposHash = shortHash($previous['sources']['wilayah_kodepos']['hash'] ?? 'unknown');
$newKodeposHash = shortHash($current['sources']['wilayah_kodepos']['hash'] ?? 'unknown');

$changelogEntry = implode("\n", [
    "## [{$newPackageVersion}] — {$releaseDate}",
    '',
    '### Data Sync',
    "- Package version: `{$previousPackageVersion}` -> `{$newPackageVersion}`",
    "- Upstream `cahyadsn/wilayah`: `{$oldWilayahHash}` -> `{$newWilayahHash}`",
    "- Upstream `cahyadsn/wilayah_kodepos`: `{$oldKodeposHash}` -> `{$newKodeposHash}`",
    '',
    '### Statistik',
    ...$statsLines,
    '',
]);

$releaseNotes = implode("\n", [
    '## Data Update Wilayah Indonesia',
    '',
    "Release package: `v{$newPackageVersion}`",
    "Tanggal sync: `{$releaseDate}`",
    '',
    '### Upstream',
    "- `cahyadsn/wilayah`: `{$oldWilayahHash}` -> `{$newWilayahHash}`",
    "- `cahyadsn/wilayah_kodepos`: `{$oldKodeposHash}` -> `{$newKodeposHash}`",
    '',
    '### Statistik Data',
    ...$statsLines,
    '',
    '### Cara update di project Anda',
    '```bash',
    'composer update aliziodev/laravel-wilayah',
    'php artisan wilayah:sync --dry-run',
    'php artisan wilayah:sync',
    '```',
    '',
]);

$existing = file_exists($changelogPath) ? file_get_contents($changelogPath) : '';
if ($existing === false) {
    fwrite(STDERR, "Failed to read {$changelogPath}\n");
    exit(1);
}

$updatedChangelog = injectChangelogEntry($existing, $changelogEntry, $newPackageVersion);

file_put_contents($changelogPath, $updatedChangelog);
file_put_contents($releaseNotesPath, $releaseNotes);

function loadVersionFile(string $path): array
{
    if (! file_exists($path)) {
        return [];
    }

    $data = include $path;

    return is_array($data) ? $data : [];
}

function shortHash(string $hash): string
{
    if ($hash === '' || $hash === 'unknown' || $hash === 'none') {
        return $hash;
    }

    return strlen($hash) > 12 ? substr($hash, 0, 12) : $hash;
}

function formatDelta(int $delta): string
{
    if ($delta > 0) {
        return '+' . $delta;
    }

    return (string) $delta;
}

function injectChangelogEntry(string $contents, string $entry, string $newVersion): string
{
    $trimmed = trim($contents);

    if ($trimmed === '') {
        return "# Changelog\n\n{$entry}\n";
    }

    $marker = "## [Unreleased]";
    if (str_contains($contents, $marker)) {
        $contents = preg_replace('/## \[Unreleased\]\s*/', "## [Unreleased]\n\n---\n\n{$entry}", $contents, 1);
    } else {
        $contents = "# Changelog\n\n{$entry}\n" . ltrim($contents);
    }

    if (preg_match('/^\[Unreleased\]: .*$/m', $contents)) {
        $contents = preg_replace(
            '/^\[Unreleased\]: .*$/m',
            "[Unreleased]: https://github.com/aliziodev/laravel-wilayah/compare/v{$newVersion}...HEAD",
            $contents,
            1
        );
    }

    if (! preg_match('/^\[' . preg_quote($newVersion, '/') . '\]:/m', $contents)) {
        $contents = rtrim($contents) . "\n[" . $newVersion . "]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v{$newVersion}\n";
    }

    return $contents;
}
