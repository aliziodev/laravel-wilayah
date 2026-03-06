<?php

uses(\Aliziodev\Wilayah\Tests\TestCase::class);
use Aliziodev\Wilayah\Facades\Wilayah;

beforeEach(function () {
    $this->seedTestData();
});

test('dapat mencari provinsi berdasarkan nama', function () {
    $result = Wilayah::search('Jawa Barat');

    expect($result['provinces'])->not->toBeEmpty();
    expect($result['provinces']->first()->name)->toEqual('JAWA BARAT');
});

test('dapat mencari kota berdasarkan nama', function () {
    $result = Wilayah::search('Bandung');

    expect($result['regencies'])->not->toBeEmpty();
    expect($result['regencies']->first()->name)->toEqual('KOTA BANDUNG');
});

test('dapat mencari desa berdasarkan nama', function () {
    $result = Wilayah::search('Arjuna');

    expect($result['villages'])->not->toBeEmpty();
    expect($result['villages']->first()->name)->toEqual('ARJUNA');
});

test('pencarian kosong mengembalikan koleksi kosong', function () {
    $result = Wilayah::search('XYZ_TIDAK_ADA_123');

    expect($result['provinces'])->toBeEmpty();
    expect($result['regencies'])->toBeEmpty();
    expect($result['districts'])->toBeEmpty();
    expect($result['villages'])->toBeEmpty();
});

test('dapat mencari berdasarkan kode pos exact', function () {
    $result = Wilayah::postalCode('40172')->get();

    expect($result)->toHaveCount(1);
    expect($result->first()->name)->toEqual('ARJUNA');
});

test('dapat mencari berdasarkan kode pos prefix', function () {
    // Cari semua kode pos yang berawalan 401
    $result = Wilayah::postalCode('401*')->get();

    expect($result->count())->toBeGreaterThanOrEqual(2);
});

test('search by postal code mengembalikan relasi hierarki', function () {
    $result = Wilayah::searchByPostalCode('40172');

    expect($result)->not->toBeEmpty();

    $village = $result->first();
    expect($village->district)->not->toBeNull();
    expect($village->district->name)->toEqual('CICENDO');
});

test('dapat mencari berdasarkan prefix kode', function () {
    $result = Wilayah::findByCodePrefix('32.73');

    // Semua district di Kota Bandung
    expect($result->count())->toBeGreaterThanOrEqual(2);
});
