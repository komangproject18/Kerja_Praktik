<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\RiwayatKelasController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
Route::get('/kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
Route::post('/kelas/{id}/naik-kelas', [KelasController::class, 'naikKelas'])->name('kelas.naik-kelas');
Route::post('/kelas/{id}/jadikan-alumni', [KelasController::class, 'jadikanAlumni'])->name('kelas.jadikan-alumni');

Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
Route::patch('/siswa/{id}/keluarkan-kelas', [SiswaController::class, 'keluarkanDariKelas'])->name('siswa.keluarkan-kelas');
Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
Route::post('/siswa/bulk-action', [SiswaController::class, 'bulkAction'])
    ->name('siswa.bulk-action');
Route::get('/siswa/{id}', [SiswaController::class, 'show'])->name('siswa.show');

Route::get('/export', [ExportController::class, 'index'])->name('export.index');
Route::get('/export/siswa', [ExportController::class, 'siswa'])->name('export.siswa');

Route::get('/mutasi', [MutasiController::class, 'index'])
    ->name('mutasi.index');

Route::get('/mutasi/masuk', [MutasiController::class, 'createMasuk'])
    ->name('mutasi.masuk');

Route::post('/mutasi/masuk', [MutasiController::class, 'storeMasuk'])
    ->name('mutasi.masuk.store');

Route::get('/mutasi/keluar', [MutasiController::class, 'createKeluar'])
    ->name('mutasi.keluar');

Route::post('/mutasi/keluar', [MutasiController::class, 'storeKeluar'])
    ->name('mutasi.keluar.store');

Route::post('/mutasi/bulk-delete', [MutasiController::class, 'bulkDelete'])
    ->name('mutasi.bulk-delete');

Route::get('/statistik', [StatistikController::class, 'index'])
    ->name('statistik.index');

Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])
    ->name('tahun-ajaran.index');

Route::get('/tahun-ajaran/create', [TahunAjaranController::class, 'create'])
    ->name('tahun-ajaran.create');

Route::post('/tahun-ajaran', [TahunAjaranController::class, 'store'])
    ->name('tahun-ajaran.store');

Route::get('/tahun-ajaran/{id}/edit', [TahunAjaranController::class, 'edit'])
    ->name('tahun-ajaran.edit');

Route::put('/tahun-ajaran/{id}', [TahunAjaranController::class, 'update'])
    ->name('tahun-ajaran.update');

Route::patch('/tahun-ajaran/{id}/activate', [TahunAjaranController::class, 'activate'])
    ->name('tahun-ajaran.activate');

Route::delete('/tahun-ajaran/{id}', [TahunAjaranController::class, 'destroy'])
    ->name('tahun-ajaran.destroy');

Route::post('/tahun-ajaran/{id}/sinkronkan', [TahunAjaranController::class, 'sinkronkan'])
    ->name('tahun-ajaran.sinkronkan');

Route::get('/alumni', [AlumniController::class, 'index'])
    ->name('alumni.index');

Route::get('/riwayat-kelas',[RiwayatKelasController::class, 'index'])->name('riwayat-kelas.index');

Route::get('/riwayat-kelas/export',[RiwayatKelasController::class, 'export'])->name('riwayat-kelas.export');
