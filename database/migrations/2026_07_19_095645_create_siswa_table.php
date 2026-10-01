<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();

            $table->string('nama', 150);
            $table->string('nis', 50)->nullable()->unique();
            $table->string('nisn', 50)->nullable()->unique();

            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();

            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('agama', 50)->nullable();

            $table->string('status_anak', 50)->nullable();
            $table->unsignedTinyInteger('anak_ke')->nullable();

            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();

            $table->string('asal_sekolah', 150)->nullable();

            $table->foreignId('kelas_id')
                ->nullable()
                ->constrained('kelas')
                ->nullOnDelete();

            $table->date('tanggal_diterima')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};