# Changelog

Semua perubahan penting pada package ini akan didokumentasikan di file ini.

Format berdasarkan [Keep a Changelog](https://keepachangelog.com/id/1.0.0/),
dan package ini mengikuti [Semantic Versioning](https://semver.org/lang/id/).

---

## [Unreleased]

---

## [1.0.2] — 2026-03-30

### Code Changes
- test: implement comprehensive unit and feature test suite using Pest (b65fb53)
- feat: implement WilayahSyncCommand, CacheService, and GitHub Actions CI for package data management (bb0b11c)
- feat: add automated release workflow and update testbench dependency version (7123c58)
- Add newline for consistency in auto-generated district, province, regency, and village files (16807a9)
- feat: Menambahkan langkah untuk menjalankan Pint sebelum pengujian di workflow CI (d82c25c)
- feat: Menambahkan `WilayahController` dengan endpoint API untuk data wilayah (provinsi, kabupaten, kecamatan, desa) beserta unit dan feature test terkait. (e36e8f8)
- feat: menambahkan GitHub Actions workflow untuk sinkronisasi data upstream, bump versi, dan membuat rilis otomatis. (c0c07c1)
---

## [1.0.1] — 2026-03-06

### Data Sync
- Package version: `1.0.0` -> `1.0.1`
- Upstream `cahyadsn/wilayah`: `unknown` -> `3be88845ef3b`
- Upstream `cahyadsn/wilayah_kodepos`: `unknown` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.0] — 2026-03-07

### Ditambahkan
- 4 level data wilayah: Provinsi, Kabupaten/Kota, Kecamatan, Desa/Kelurahan
- Data kode pos terintegrasi dari `cahyadsn/wilayah_kodepos`
- Facade `Wilayah::` untuk akses semua fitur
- Cache service dengan TTL konfigurabel per tipe query
- Search: basic, full-text (MySQL/PostgreSQL), address search, kode pos
- Hierarchy service: `toAddress()`, `toShortAddress()`, `ancestors()`
- Dropdown/Select formatter untuk cascade select UI
- Pagination support: `paginate()`, `simplePaginate()`, `cursorPaginate()`
- Artisan commands: `wilayah:install`, `wilayah:seed`, `wilayah:sync`, `wilayah:version`, `wilayah:cache-clear`
- Fitur opsional: islands, areas, populations (toggle via config)
- Auto-sync CI/CD via GitHub Actions dari upstream `cahyadsn/wilayah`
- Support Laravel 11.x dan 12.x (PHP ^8.2)
- Support MySQL dan PostgreSQL

[Unreleased]: https://github.com/aliziodev/laravel-wilayah/compare/v1.0.2...HEAD
[1.0.0]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.0
[1.0.1]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.1
[1.0.2]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.2
