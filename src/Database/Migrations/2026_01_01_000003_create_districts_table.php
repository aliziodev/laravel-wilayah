<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('wilayah.table_names.districts', 'districts'), function (Blueprint $table) {
            $table->mediumIncrements('id');                       // MEDIUMINT UNSIGNED — max 16.7 juta
            $table->char('code', 8)->unique();                    // '11.01.01'
            $table->unsignedSmallInteger('regency_id');
            $table->string('name', 100);
            $table->index('name');
            $table->index(['regency_id', 'name']);
            $table->timestamps();

            $table->foreign('regency_id')
                ->references('id')
                ->on(config('wilayah.table_names.regencies', 'regencies'))
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('wilayah.table_names.districts', 'districts'));
    }
};
