<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration opsional — hanya dijalankan jika config('wilayah.features.islands') = true
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! config('wilayah.features.islands', false)) {
            return;
        }

        Schema::create(config('wilayah.table_names.islands', 'islands'), function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('code', 12)->index();
            $table->unsignedSmallInteger('regency_id')->nullable();
            $table->string('name', 255);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->boolean('is_named')->default(true);           // false = Tanpa Berpenghuni
            $table->text('notes')->nullable();
            $table->index('name');
            $table->timestamps();

            $table->foreign('regency_id')
                ->references('id')
                ->on(config('wilayah.table_names.regencies', 'regencies'))
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('wilayah.table_names.islands', 'islands'));
    }
};
