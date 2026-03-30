<?php

use Aliziodev\Wilayah\Facades\Wilayah;

beforeEach(function () {
    $this->seedTestData();
});

test('dapat membuat dropdown provinsi', function () {
    $dropdown = Wilayah::forDropdown('provinces');

    expect($dropdown)->toBeArray();
    expect($dropdown)->toHaveKey('32');
    expect($dropdown['32'])->toEqual('JAWA BARAT');
});

test('dapat membuat dropdown kabupaten filter provinsi', function () {
    $dropdown = Wilayah::forDropdown('regencies', province: '32');

    expect($dropdown)->toBeArray();
    expect($dropdown)->toHaveKey('32.73');
    $this->assertArrayNotHasKey('11.01', $dropdown);
    // Bukan di Jawa Barat
});

test('dapat membuat dropdown kecamatan filter kabupaten', function () {
    $dropdown = Wilayah::forDropdown('districts', regency: '32.73');

    expect($dropdown)->toBeArray();
    expect($dropdown)->toContain('CICENDO');
    expect($dropdown)->toContain('ANDIR');
});

test('dapat membuat format select dengan value dan label', function () {
    $select = Wilayah::forSelect('provinces');

    expect($select)->toBeArray();
    expect($select[0])->toHaveKey('value');
    expect($select[0])->toHaveKey('label');
});

test('level tidak valid melempar exception', function () {
    $this->expectException(InvalidArgumentException::class);
    Wilayah::forDropdown('invalid_level');
});
