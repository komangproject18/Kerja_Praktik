<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wali', function (Blueprint $table) {
            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->string('nama_wali', 150)->nullable();
            $table->text('alamat_wali')->nullable();
            $table->string('no_hp_wali', 20)->nullable();
            $table->string('pekerjaan_wali', 100)->nullable();

            $table->timestamps();

            $table->unique('siswa_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wali');
    }
};