<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('wilayah.table_names.regencies', 'regencies'), function (Blueprint $table) {
            $table->smallIncrements('id');                        // SMALLINT UNSIGNED — max 65.535
            $table->char('code', 5)->unique();                    // '11.01', '32.73'
            $table->unsignedTinyInteger('province_id');
            $table->string('name', 100);
            $table->tinyInteger('type')->default(0);              // 0=kabupaten, 1=kota
            $table->index('name');
            $table->index(['province_id', 'name']);
            $table->timestamps();

            $table->foreign('province_id')
                ->references('id')
                ->on(config('wilayah.table_names.provinces', 'provinces'))
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('wilayah.table_names.regencies', 'regencies'));
    }
};
