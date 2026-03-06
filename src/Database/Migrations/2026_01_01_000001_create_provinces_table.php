<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('wilayah.table_names.provinces', 'provinces'), function (Blueprint $table) {
            $table->tinyIncrements('id');                         // TINYINT UNSIGNED — cukup untuk 38 provinsi
            $table->char('code', 2)->unique();                    // '11', '32', '73'
            $table->string('name', 100);
            $table->index('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('wilayah.table_names.provinces', 'provinces'));
    }
};
