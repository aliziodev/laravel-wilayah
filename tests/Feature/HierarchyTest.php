<?php

uses(\Aliziodev\Wilayah\Tests\TestCase::class);
use Aliziodev\Wilayah\Facades\Wilayah;

beforeEach(function () {
    $this->seedTestData();
});

test('dapat load hierarki dari kode desa', function () {
    $hierarchy = Wilayah::hierarchy('32.73.07.1001');

    expect($hierarchy->village->name)->toEqual('ARJUNA');
    expect($hierarchy->district->name)->toEqual('CICENDO');
    expect($hierarchy->regency->name)->toEqual('KOTA BANDUNG');
    expect($hierarchy->province->name)->toEqual('JAWA BARAT');
});

test('dapat load hierarki dari kode kecamatan', function () {
    $hierarchy = Wilayah::hierarchy('32.73.07');

    expect($hierarchy->village)->toBeNull();
    expect($hierarchy->district->name)->toEqual('CICENDO');
    expect($hierarchy->regency->name)->toEqual('KOTA BANDUNG');
    expect($hierarchy->province->name)->toEqual('JAWA BARAT');
});

test('dapat format alamat lengkap', function () {
    $hierarchy = Wilayah::hierarchy('32.73.07.1001');
    $address = $hierarchy->toAddress();

    $this->assertStringContainsString('ARJUNA', $address);
    $this->assertStringContainsString('CICENDO', $address);
    $this->assertStringContainsString('KOTA BANDUNG', $address);
    $this->assertStringContainsString('JAWA BARAT', $address);
    $this->assertStringContainsString('40172', $address);
    // kode pos
});

test('dapat format alamat pendek', function () {
    $hierarchy = Wilayah::hierarchy('32.73.07.1001');
    $shortAddress = $hierarchy->toShortAddress();

    $this->assertStringContainsString('CICENDO', $shortAddress);
    $this->assertStringContainsString('KOTA BANDUNG', $shortAddress);
    $this->assertStringContainsString('JAWA BARAT', $shortAddress);
    $this->assertStringNotContainsString('ARJUNA', $shortAddress);
    // desa tidak tampil di short
});

test('dapat mendapatkan ancestors', function () {
    $hierarchy = Wilayah::hierarchy('32.73.07.1001');
    $ancestors = $hierarchy->ancestors();

    expect($ancestors)->toHaveCount(4);
    // village, district, regency, province
    $names = collect($ancestors)->pluck('name')->toArray();
    expect($names)->toContain('ARJUNA');
    expect($names)->toContain('CICENDO');
    expect($names)->toContain('KOTA BANDUNG');
    expect($names)->toContain('JAWA BARAT');
});

test('kode tidak valid melempar exception', function () {
    $this->expectException(\InvalidArgumentException::class);
    Wilayah::hierarchy('99.99.99.9999');
});
