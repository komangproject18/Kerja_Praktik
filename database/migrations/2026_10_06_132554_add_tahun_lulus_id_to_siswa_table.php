<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->foreignId('tahun_lulus_id')
                ->nullable()
                ->after('status_siswa')
                ->constrained('tahun_ajaran')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['tahun_lulus_id']);
            $table->dropColumn('tahun_lulus_id');
        });
    }
};