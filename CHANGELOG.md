# Changelog

Semua perubahan penting pada package ini akan didokumentasikan di file ini.

Format berdasarkan [Keep a Changelog](https://keepachangelog.com/id/1.0.0/),
dan package ini mengikuti [Semantic Versioning](https://semver.org/lang/id/).

---

## [Unreleased]

---

## [1.0.37] — 2026-07-16

### Data Sync
- Package version: `1.0.36` -> `1.0.37`
- Upstream `cahyadsn/wilayah`: `cae306278e5b` -> `4bd1e4c4aac2`
- Upstream `cahyadsn/wilayah_kodepos`: `e007157ccd3b` -> `e007157ccd3b`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.36] — 2026-07-13

### Data Sync
- Package version: `1.0.35` -> `1.0.36`
- Upstream `cahyadsn/wilayah`: `cae306278e5b` -> `cae306278e5b`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `e007157ccd3b`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.35] — 2026-07-11

### Data Sync
- Package version: `1.0.34` -> `1.0.35`
- Upstream `cahyadsn/wilayah`: `142abab57310` -> `cae306278e5b`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.34] — 2026-07-10

### Data Sync
- Package version: `1.0.33` -> `1.0.34`
- Upstream `cahyadsn/wilayah`: `6ccdf41288dc` -> `142abab57310`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.33] — 2026-07-09

### Data Sync
- Package version: `1.0.32` -> `1.0.33`
- Upstream `cahyadsn/wilayah`: `b8524cf04c54` -> `6ccdf41288dc`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.32] — 2026-07-07

### Code Changes
- ci: allow installing EOL Laravel 11 despite security advisories (a780224)
- fix: resolve "Unknown named parameter $province" on wilayah:seed (3cbe84c)
### Diperbaiki
- `wilayah:seed` error `Unknown named parameter $province` — parameter seeder anak kini dikirim dengan kunci yang sesuai nama argumen `run(array $params)` sehingga tidak salah di-spread sebagai named arguments oleh `Seeder::__invoke`
- Filter `--province` kini benar-benar diteruskan ke seeder anak (sebelumnya di-drop diam-diam pada jalur `db:seed`) dan diterapkan juga di `ProvinceSeeder`
- `wilayah:seed --fresh` kini bekerja di PostgreSQL/SQLite — `SET FOREIGN_KEY_CHECKS` (khusus MySQL) diganti `Schema::disableForeignKeyConstraints()`
- `wilayah:seed` dan `wilayah:sync` kini men-set container + command pada seeder sehingga seeder anak menampilkan progres di console

### Ditambahkan
- Regression test untuk `wilayah:seed --province` dan jalur container (`db:seed`)
- `pint.json` — folder `data/` (hasil generate) dikecualikan dari formatter

---

## [1.0.31] — 2026-07-02

### Data Sync
- Package version: `1.0.30` -> `1.0.31`
- Upstream `cahyadsn/wilayah`: `3ffc1f6c308c` -> `b8524cf04c54`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.30] — 2026-06-30

### Data Sync
- Package version: `1.0.29` -> `1.0.30`
- Upstream `cahyadsn/wilayah`: `daba9d646f50` -> `3ffc1f6c308c`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.29] — 2026-06-27

### Data Sync
- Package version: `1.0.28` -> `1.0.29`
- Upstream `cahyadsn/wilayah`: `9c3ceb195a04` -> `daba9d646f50`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.28] — 2026-06-26

### Data Sync
- Package version: `1.0.27` -> `1.0.28`
- Upstream `cahyadsn/wilayah`: `9fc5382322cd` -> `9c3ceb195a04`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.27] — 2026-06-18

### Data Sync
- Package version: `1.0.26` -> `1.0.27`
- Upstream `cahyadsn/wilayah`: `e7a6a7031935` -> `9fc5382322cd`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.26] — 2026-06-12

### Data Sync
- Package version: `1.0.25` -> `1.0.26`
- Upstream `cahyadsn/wilayah`: `5793cd9fa320` -> `e7a6a7031935`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.25] — 2026-06-10

### Data Sync
- Package version: `1.0.24` -> `1.0.25`
- Upstream `cahyadsn/wilayah`: `a7143760de0e` -> `5793cd9fa320`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.24] — 2026-06-09

### Data Sync
- Package version: `1.0.23` -> `1.0.24`
- Upstream `cahyadsn/wilayah`: `1dd6c8b5074f` -> `a7143760de0e`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.23] — 2026-06-08

### Data Sync
- Package version: `1.0.22` -> `1.0.23`
- Upstream `cahyadsn/wilayah`: `6ff9b8a2764c` -> `1dd6c8b5074f`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.22] — 2026-06-07

### Data Sync
- Package version: `1.0.21` -> `1.0.22`
- Upstream `cahyadsn/wilayah`: `e4bbd63fe6f5` -> `6ff9b8a2764c`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.21] — 2026-06-05

### Data Sync
- Package version: `1.0.20` -> `1.0.21`
- Upstream `cahyadsn/wilayah`: `5397f5815f8b` -> `e4bbd63fe6f5`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.20] — 2026-06-04

### Data Sync
- Package version: `1.0.19` -> `1.0.20`
- Upstream `cahyadsn/wilayah`: `7945034c5035` -> `5397f5815f8b`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.19] — 2026-06-03

### Data Sync
- Package version: `1.0.18` -> `1.0.19`
- Upstream `cahyadsn/wilayah`: `985bda5f8405` -> `7945034c5035`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.18] — 2026-06-02

### Data Sync
- Package version: `1.0.17` -> `1.0.18`
- Upstream `cahyadsn/wilayah`: `f30be9ce9a5b` -> `985bda5f8405`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.17] — 2026-06-01

### Data Sync
- Package version: `1.0.16` -> `1.0.17`
- Upstream `cahyadsn/wilayah`: `85e7b74fcc9a` -> `f30be9ce9a5b`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.16] — 2026-05-31

### Data Sync
- Package version: `1.0.15` -> `1.0.16`
- Upstream `cahyadsn/wilayah`: `ecefcd06cf73` -> `85e7b74fcc9a`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.15] — 2026-05-30

### Data Sync
- Package version: `1.0.14` -> `1.0.15`
- Upstream `cahyadsn/wilayah`: `166163a750f4` -> `ecefcd06cf73`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.14] — 2026-05-29

### Data Sync
- Package version: `1.0.13` -> `1.0.14`
- Upstream `cahyadsn/wilayah`: `0066c7e24839` -> `166163a750f4`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.13] — 2026-05-28

### Data Sync
- Package version: `1.0.12` -> `1.0.13`
- Upstream `cahyadsn/wilayah`: `dd8ae6eb9784` -> `0066c7e24839`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.12] — 2026-05-27

### Data Sync
- Package version: `1.0.11` -> `1.0.12`
- Upstream `cahyadsn/wilayah`: `c921c2feb409` -> `dd8ae6eb9784`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.11] — 2026-05-23

### Data Sync
- Package version: `1.0.10` -> `1.0.11`
- Upstream `cahyadsn/wilayah`: `0817da9ec5a1` -> `c921c2feb409`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.10] — 2026-05-20

### Data Sync
- Package version: `1.0.9` -> `1.0.10`
- Upstream `cahyadsn/wilayah`: `2339d61be054` -> `0817da9ec5a1`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.9] — 2026-05-19

### Data Sync
- Package version: `1.0.8` -> `1.0.9`
- Upstream `cahyadsn/wilayah`: `942da2416ccb` -> `2339d61be054`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.8] — 2026-05-15

### Data Sync
- Package version: `1.0.7` -> `1.0.8`
- Upstream `cahyadsn/wilayah`: `cb05064af1cc` -> `942da2416ccb`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.7] — 2026-05-11

### Data Sync
- Package version: `1.0.6` -> `1.0.7`
- Upstream `cahyadsn/wilayah`: `cab2b18668c1` -> `cb05064af1cc`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.6] — 2026-05-08

### Data Sync
- Package version: `1.0.5` -> `1.0.6`
- Upstream `cahyadsn/wilayah`: `f1b6f87d9d5d` -> `cab2b18668c1`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.5] — 2026-05-07

### Data Sync
- Package version: `1.0.4` -> `1.0.5`
- Upstream `cahyadsn/wilayah`: `3be88845ef3b` -> `f1b6f87d9d5d`
- Upstream `cahyadsn/wilayah_kodepos`: `4fa8c592a581` -> `4fa8c592a581`

### Statistik
- Provinces: 38 -> 38 (0)
- Regencies: 514 -> 514 (0)
- Districts: 7265 -> 7265 (0)
- Villages: 83345 -> 83345 (0)
---

## [1.0.4] — 2026-03-30

### Code Changes
- feat: add automated workflow to sync upstream data and create releases (746cfc5)
---

## [1.0.3] — 2026-03-30

### Code Changes
- chore: remove hardcoded version from composer.json (917f9ef)
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

[Unreleased]: https://github.com/aliziodev/laravel-wilayah/compare/v1.0.37...HEAD
[1.0.0]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.0
[1.0.1]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.1
[1.0.2]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.2
[1.0.3]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.3
[1.0.4]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.4
[1.0.5]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.5
[1.0.6]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.6
[1.0.7]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.7
[1.0.8]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.8
[1.0.9]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.9
[1.0.10]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.10
[1.0.11]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.11
[1.0.12]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.12
[1.0.13]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.13
[1.0.14]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.14
[1.0.15]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.15
[1.0.16]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.16
[1.0.17]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.17
[1.0.18]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.18
[1.0.19]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.19
[1.0.20]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.20
[1.0.21]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.21
[1.0.22]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.22
[1.0.23]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.23
[1.0.24]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.24
[1.0.25]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.25
[1.0.26]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.26
[1.0.27]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.27
[1.0.28]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.28
[1.0.29]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.29
[1.0.30]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.30
[1.0.31]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.31
[1.0.32]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.32
[1.0.33]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.33
[1.0.34]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.34
[1.0.35]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.35
[1.0.36]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.36
[1.0.37]: https://github.com/aliziodev/laravel-wilayah/releases/tag/v1.0.37
