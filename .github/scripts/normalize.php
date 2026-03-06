<?php

/**
 * Normalize SQL upstream → PHP array data files untuk package.
 *
 * Mendukung 2 mode input:
 *
 * 1. RAW URL (direkomendasikan untuk CI/CD — tidak perlu git clone):
 *    php normalize.php \
 *      https://raw.githubusercontent.com/cahyadsn/wilayah/master/db/wilayah.sql \
 *      https://raw.githubusercontent.com/cahyadsn/wilayah_kodepos/master/db/wilayah_kodepos.sql
 *
 * 2. File lokal (untuk development):
 *    php normalize.php /path/to/wilayah.sql /path/to/wilayah_kodepos.sql
 *
 * Usage:
 *    normalize.php <wilayah.sql|URL> [kodepos.sql|URL] [--branch=master]
 */

// ─────────────────────────────────────────────
// Konfigurasi upstream per-repo
// Branch bisa beda antar repo!
// ─────────────────────────────────────────────
const UPSTREAM_BASE = 'https://raw.githubusercontent.com/cahyadsn';

$UPSTREAM_REPOS = [
    'wilayah'         => ['branch' => 'master', 'repo' => 'cahyadsn/wilayah'],
    'wilayah_kodepos' => ['branch' => 'main',   'repo' => 'cahyadsn/wilayah_kodepos'],
];

// Bangun default URL dari config per-repo
$DEFAULT_WILAYAH_URL = UPSTREAM_BASE
    . '/wilayah/refs/heads/'
    . $UPSTREAM_REPOS['wilayah']['branch']
    . '/db/wilayah.sql';

$DEFAULT_KODEPOS_URL = UPSTREAM_BASE
    . '/wilayah_kodepos/refs/heads/'
    . $UPSTREAM_REPOS['wilayah_kodepos']['branch']
    . '/db/wilayah_kodepos.sql';

// ─────────────────────────────────────────────
// Resolve argumen — URL atau file lokal
// ─────────────────────────────────────────────
$wilayahInput = $argv[1] ?? $DEFAULT_WILAYAH_URL;
$kodeposInput = $argv[2] ?? $DEFAULT_KODEPOS_URL;


$outputDir = __DIR__ . '/../../data';

echo "=== Normalizer Wilayah Indonesia ===\n";
echo "Wilayah SQL : {$wilayahInput}\n";
echo "Kodepos SQL : {$kodeposInput}\n";
echo "Output dir  : {$outputDir}\n\n";

// ─────────────────────────────────────────────
// Helper: buka input sebagai stream (URL atau file)
// Streaming penting agar tidak OOM untuk file >20MB
// ─────────────────────────────────────────────
function openStream(string $input)
{
    if (str_starts_with($input, 'http://') || str_starts_with($input, 'https://')) {
        $context = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'header'  => "User-Agent: aliziodev-normalizer/1.0\r\n",
                'timeout' => 120,
            ],
            'ssl' => [
                'verify_peer'      => true,
                'verify_peer_name' => true,
            ],
        ]);

        $handle = @fopen($input, 'r', false, $context);
        if (! $handle) {
            echo "ERROR: Gagal fetch URL: {$input}\n";
            exit(1);
        }
        return $handle;
    }

    // Local file
    if (! file_exists($input)) {
        echo "ERROR: File tidak ditemukan: {$input}\n";
        exit(1);
    }
    return fopen($input, 'r');
}

// ─────────────────────────────────────────────
// Helper: cek commit hash upstream via GitHub API
// Per-repo karena branch bisa berbeda
// ─────────────────────────────────────────────
function getUpstreamHash(string $repo, string $branch): string
{
    $apiUrl  = "https://api.github.com/repos/{$repo}/commits/{$branch}";
    $context = stream_context_create([
        'http' => [
            'header'  => "User-Agent: aliziodev-normalizer/1.0\r\nAccept: application/vnd.github.v3+json\r\n",
            'timeout' => 15,
        ],
    ]);

    $response = @file_get_contents($apiUrl, false, $context);
    if ($response) {
        $data = json_decode($response, true);
        return $data['sha'] ?? 'unknown';
    }

    return 'unknown';
}



// ─────────────────────────────────────────────
// 1. Parse wilayah.sql → rows (kode, nama)
// ─────────────────────────────────────────────
echo "[1/5] Parsing wilayah SQL (stream)...\n";

$rows         = [];
$handle       = openStream($wilayahInput);
$insertBuffer = '';

while (($line = fgets($handle)) !== false) {
    $line = rtrim($line);
    if (stripos($line, 'INSERT INTO') !== false) {
        $insertBuffer = $line;
    } elseif ($insertBuffer !== '') {
        $insertBuffer .= ' ' . $line;
    }

    if ($insertBuffer !== '' && str_ends_with(rtrim($insertBuffer), ';')) {
        // Parse VALUES dari INSERT INTO `wilayah` VALUES ('kode', 'nama');
        preg_match_all("/\('([^']+)',\s*'([^']*)'\)/", $insertBuffer, $matches, PREG_SET_ORDER);
        foreach ($matches as $m) {
            $rows[$m[1]] = $m[2];
        }
        $insertBuffer = '';
    }
}
fclose($handle);

echo "   Ditemukan " . count($rows) . " wilayah\n";

// ─────────────────────────────────────────────
// 2. Parse wilayah_kodepos.sql → kodepos map
// ─────────────────────────────────────────────
echo "[2/5] Parsing kodepos SQL (stream)...\n";

$kodeposMap = [];
if ($kodeposInput) {
    // Kodespos bisa berupa URL atau file lokal
    $handle = openStream($kodeposInput);
    $insertBuffer = '';
    while (($line = fgets($handle)) !== false) {
        $line = rtrim($line);
        if (stripos($line, 'INSERT INTO') !== false) {
            $insertBuffer = $line;
        } elseif ($insertBuffer !== '') {
            $insertBuffer .= ' ' . $line;
        }
        if ($insertBuffer !== '' && str_ends_with(rtrim($insertBuffer), ';')) {
            preg_match_all("/\('([^']+)',\s*'([^']*)'\)/", $insertBuffer, $matches, PREG_SET_ORDER);
            foreach ($matches as $m) {
                $kodeposMap[$m[1]] = $m[2];
            }
            $insertBuffer = '';
        }
    }
    fclose($handle);
    echo "   Ditemukan " . count($kodeposMap) . " kode pos\n";
} else {
    echo "   (kodepos dilewati)\n";
}

// ─────────────────────────────────────────────
// 3. Klasifikasikan berdasarkan panjang kode
// ─────────────────────────────────────────────
echo "[3/5] Mengklasifikasikan level wilayah...\n";

$provinces = [];
$regencies = [];
$districts  = [];
$villages   = [];

foreach ($rows as $code => $name) {
    $len = strlen($code);

    if ($len === 2) {
        $provinces[] = ['code' => $code, 'name' => $name];
    } elseif ($len === 5) {
        // Deteksi tipe: Kota atau Kabupaten
        $type = (
            stripos($name, 'KOTA') === 0 ||
            stripos($name, 'KOTA ') !== false
        ) ? 1 : 0;
        $regencies[] = ['code' => $code, 'name' => $name, 'type' => $type];
    } elseif ($len === 8) {
        $districts[] = ['code' => $code, 'name' => $name];
    } else {
        // Deteksi tipe: Kelurahan atau Desa
        $type = (
            stripos($name, 'KEL ') === 0 ||
            stripos($name, 'KELURAHAN') === 0
        ) ? 1 : 0;
        $villages[$code] = [
            'code'        => $code,
            'name'        => $name,
            'type'        => $type,
            'postal_code' => $kodeposMap[$code] ?? null,
        ];
    }
}

echo "   Provinsi     : " . count($provinces) . "\n";
echo "   Kab/Kota     : " . count($regencies) . "\n";
echo "   Kecamatan    : " . count($districts) . "\n";
echo "   Desa/Kel     : " . count($villages) . "\n";

// ─────────────────────────────────────────────
// 4. Tulis ke data/*.php
// ─────────────────────────────────────────────
echo "[4/5] Menulis file PHP arrays...\n";

$header = "<?php\n// Auto-generated by CI/CD. DO NOT EDIT MANUALLY.\n// Source: cahyadsn/wilayah\nreturn [\n";
$footer = "];\n";

// provinces.php
$content = $header;
foreach ($provinces as $p) {
    $content .= "    ['code' => '{$p['code']}', 'name' => " . var_export($p['name'], true) . "],\n";
}
$content .= $footer;
file_put_contents("{$outputDir}/provinces.php", $content);
echo "   ✓ provinces.php\n";

// regencies.php
$content = $header;
foreach ($regencies as $r) {
    $content .= "    ['code' => '{$r['code']}', 'name' => " . var_export($r['name'], true) . ", 'type' => {$r['type']}],\n";
}
$content .= $footer;
file_put_contents("{$outputDir}/regencies.php", $content);
echo "   ✓ regencies.php\n";

// districts per provinsi
@mkdir("{$outputDir}/districts", 0755, true);
$byProv = [];
foreach ($districts as $d) {
    $provCode = substr($d['code'], 0, 2);
    $byProv[$provCode][] = $d;
}
foreach ($byProv as $provCode => $items) {
    $content = $header;
    foreach ($items as $d) {
        $content .= "    ['code' => '{$d['code']}', 'name' => " . var_export($d['name'], true) . "],\n";
    }
    $content .= $footer;
    file_put_contents("{$outputDir}/districts/districts_{$provCode}.php", $content);
}
echo "   ✓ districts/ (" . count($byProv) . " file)\n";

// villages per provinsi
@mkdir("{$outputDir}/villages", 0755, true);
$byProv = [];
foreach ($villages as $v) {
    $provCode = substr($v['code'], 0, 2);
    $byProv[$provCode][] = $v;
}
foreach ($byProv as $provCode => $items) {
    $content = $header;
    foreach ($items as $v) {
        $postalCode = $v['postal_code'] ? "'{$v['postal_code']}'" : 'null';
        $content .= "    ['code' => '{$v['code']}', 'name' => " . var_export($v['name'], true) . ", 'type' => {$v['type']}, 'postal_code' => {$postalCode}],\n";
    }
    $content .= $footer;
    file_put_contents("{$outputDir}/villages/villages_{$provCode}.php", $content);
}
echo "   ✓ villages/ (" . count($byProv) . " file)\n";

// ─────────────────────────────────────────────
// 5. version.php
// ─────────────────────────────────────────────
echo "[5/5] Menulis version.php...\n";

$wilayahHash = getUpstreamHash(
    $UPSTREAM_REPOS['wilayah']['repo'],
    $UPSTREAM_REPOS['wilayah']['branch']
);
$kodeposHash = getUpstreamHash(
    $UPSTREAM_REPOS['wilayah_kodepos']['repo'],
    $UPSTREAM_REPOS['wilayah_kodepos']['branch']
);
$hash = substr($wilayahHash, 0, 12) . '-' . substr($kodeposHash, 0, 12);

$versionContent = "<?php\n// Auto-generated by CI/CD. DO NOT EDIT MANUALLY.\nreturn [\n";
$versionContent .= "    'version'     => '" . date('Y.m.d') . "',\n";
$versionContent .= "    'data_date'   => '" . date('Y-m-d') . "',\n";
$versionContent .= "    'source_hash' => '{$hash}',\n";
$versionContent .= "    'generated_at' => '" . gmdate('c') . "',\n";
$versionContent .= "    'sources'     => [\n";
$versionContent .= "        'wilayah' => [\n";
$versionContent .= "            'repo' => '" . $UPSTREAM_REPOS['wilayah']['repo'] . "',\n";
$versionContent .= "            'branch' => '" . $UPSTREAM_REPOS['wilayah']['branch'] . "',\n";
$versionContent .= "            'hash' => '{$wilayahHash}',\n";
$versionContent .= "        ],\n";
$versionContent .= "        'wilayah_kodepos' => [\n";
$versionContent .= "            'repo' => '" . $UPSTREAM_REPOS['wilayah_kodepos']['repo'] . "',\n";
$versionContent .= "            'branch' => '" . $UPSTREAM_REPOS['wilayah_kodepos']['branch'] . "',\n";
$versionContent .= "            'hash' => '{$kodeposHash}',\n";
$versionContent .= "        ],\n";
$versionContent .= "    ],\n";
$versionContent .= "    'counts'      => [\n";
$versionContent .= "        'provinces' => " . count($provinces) . ",\n";
$versionContent .= "        'regencies' => " . count($regencies) . ",\n";
$versionContent .= "        'districts' => " . count($districts) . ",\n";
$versionContent .= "        'villages'  => " . count($villages) . ",\n";
$versionContent .= "    ],\n";
$versionContent .= "];\n";

file_put_contents("{$outputDir}/version.php", $versionContent);
echo "   ✓ version.php\n";

echo "\n=== Normalisasi selesai! ===\n";
