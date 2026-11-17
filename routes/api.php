<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\RekapController;

/*
| Baca buku (GET) bersifat publik. Tulis buku & rekap dilindungi.
| Endpoint API dipakai oleh halaman /buku (fetch dari browser), jadi dilindungi
| memakai session login ("web" + "auth") dan permission Spatie.
| Request tanpa login mendapat 401 JSON; tanpa permission mendapat 403 JSON.
*/
// Membaca daftar & detail buku: publik (tamu boleh melihat).
Route::middleware('web')->group(function () {
    Route::get('buku', [BukuController::class, 'index']);
    Route::get('buku/{buku}', [BukuController::class, 'show']);
});

Route::middleware(['web', 'auth'])->group(function () {

    Route::middleware('can:kelola-buku')->group(function () {
        Route::post('buku', [BukuController::class, 'store']);
        Route::match(['put', 'patch'], 'buku/{buku}', [BukuController::class, 'update']);
        Route::delete('buku/{buku}', [BukuController::class, 'destroy']);
    });

    Route::middleware('permission:lihat laporan perpustakaan')->group(function () {
        Route::get('rekap/buku-per-kategori', [RekapController::class, 'bukuPerKategori']);
        Route::get('rekap/peminjaman-per-buku', [RekapController::class, 'peminjamanPerBuku']);
    });
});

// DIAGNOSTIK SEMENTARA: hapus setelah masalah ketemu
Route::get('/diag', function () {
    $out = [];
    $cek = [
        'koneksi_db'     => fn () => \Illuminate\Support\Facades\DB::select('select 1 as ok'),
        'tabel_users'    => fn () => \Illuminate\Support\Facades\Schema::hasTable('users'),
        'jumlah_users'   => fn () => \Illuminate\Support\Facades\DB::table('users')->count(),
        'tabel_sessions' => fn () => \Illuminate\Support\Facades\Schema::hasTable('sessions'),
        'tabel_roles'    => fn () => \Illuminate\Support\Facades\Schema::hasTable('roles'),
        'tabel_cache'    => fn () => \Illuminate\Support\Facades\Schema::hasTable('cache'),
    ];
    foreach ($cek as $nama => $fn) {
        try {
            $out[$nama] = $fn();
        } catch (\Throwable $e) {
            $out[$nama] = get_class($e) . ': ' . \Illuminate\Support\Str::limit($e->getMessage(), 200);
        }
    }
    return response()->json($out);
});