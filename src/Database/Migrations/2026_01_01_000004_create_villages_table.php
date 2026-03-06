<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('wilayah.table_names.villages', 'villages'), function (Blueprint $table) {
            $table->increments('id');                             // INT UNSIGNED — max 4.2 miliar
            $table->string('code', 13)->unique();                 // '11.01.01.2001'
            $table->unsignedMediumInteger('district_id');
            $table->string('name', 100);
            $table->tinyInteger('type')->default(0);              // 0=desa, 1=kelurahan
            $table->char('postal_code', 5)->nullable();
            $table->index(['district_id', 'name']);
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->fullText('name');
            }
            $table->index('postal_code');
            $table->timestamps();

            $table->foreign('district_id')
                ->references('id')
                ->on(config('wilayah.table_names.districts', 'districts'))
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('wilayah.table_names.villages', 'villages'));
    }
};
