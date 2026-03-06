<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration opsional — hanya dijalankan jika config('wilayah.features.populations') = true
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! config('wilayah.features.populations', false)) {
            return;
        }

        Schema::create(config('wilayah.table_names.region_populations', 'region_populations'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_type', 30);
            $table->unsignedInteger('model_id');
            $table->unsignedInteger('male')->default(0);
            $table->unsignedInteger('female')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->unsignedSmallInteger('year')->default(0);
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('wilayah.table_names.region_populations', 'region_populations'));
    }
};
