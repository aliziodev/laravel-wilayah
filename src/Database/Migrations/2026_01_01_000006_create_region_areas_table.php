<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration opsional — hanya dijalankan jika config('wilayah.features.areas') = true
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! config('wilayah.features.areas', false)) {
            return;
        }

        Schema::create(config('wilayah.table_names.region_areas', 'region_areas'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_type', 30);                     // 'province', 'regency'
            $table->unsignedInteger('model_id');
            $table->decimal('area_km2', 12, 3);
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('wilayah.table_names.region_areas', 'region_areas'));
    }
};
