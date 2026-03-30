<?php

uses(TestCase::class);
use Aliziodev\Wilayah\Http\Controllers\WilayahController;
use Aliziodev\Wilayah\Tests\TestCase;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\getJson;

beforeEach(function () {
    // Register temporary routes for testing the controller
    Route::get('api/wilayah/provinces', [WilayahController::class, 'provinces']);
    Route::get('api/wilayah/regencies', [WilayahController::class, 'regencies']);
    Route::get('api/wilayah/districts', [WilayahController::class, 'districts']);
    Route::get('api/wilayah/villages', [WilayahController::class, 'villages']);

    $this->seedTestData();
});

test('provinces endpoint returns available provinces as value-label format', function () {
    getJson('api/wilayah/provinces')
        ->assertOk()
        ->assertJsonCount(3)
        ->assertJsonFragment(['value' => '11', 'label' => 'ACEH'])
        ->assertJsonFragment(['value' => '32', 'label' => 'JAWA BARAT']);
});

test('regencies endpoint requires province query parameter', function () {
    getJson('api/wilayah/regencies')
        ->assertStatus(400)
        ->assertJson(['message' => 'Query parameter "province" is required.']);
});

test('regencies endpoint returns regencies for a valid province', function () {
    getJson('api/wilayah/regencies?province=32')
        ->assertOk()
        ->assertJsonCount(2)
        ->assertJsonFragment(['value' => '32.73', 'label' => 'KOTA BANDUNG']);
});

test('districts endpoint requires regency query parameter', function () {
    getJson('api/wilayah/districts')
        ->assertStatus(400)
        ->assertJson(['message' => 'Query parameter "regency" is required.']);
});

test('districts endpoint returns districts for a valid regency', function () {
    getJson('api/wilayah/districts?regency=32.73')
        ->assertOk()
        ->assertJsonCount(2)
        ->assertJsonFragment(['value' => '32.73.08', 'label' => 'ANDIR']);
});

test('villages endpoint requires district query parameter', function () {
    getJson('api/wilayah/villages')
        ->assertStatus(400)
        ->assertJson(['message' => 'Query parameter "district" is required.']);
});

test('villages endpoint returns villages for a valid district', function () {
    getJson('api/wilayah/villages?district=32.73.07')
        ->assertOk()
        ->assertJsonCount(2)
        ->assertJsonFragment(['value' => '32.73.07.1001', 'label' => 'ARJUNA']);
});
