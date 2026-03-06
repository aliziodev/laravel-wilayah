# Changelog

Semua perubahan penting pada package ini akan didokumentasikan di file ini.

Format berdasarkan [Keep a Changelog](https://keepachangelog.com/id/1.0.0/),
dan package ini mengikuti [Semantic Versioning](https://semver.org/lang/id/).

---

## [Unreleased]

---

## [1.0.0] — 2025-01-01

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

[Unreleased]: https://github.com/aliziodev/laravel-wilayah/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.0
