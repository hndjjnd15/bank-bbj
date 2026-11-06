<?php

use App\Http\Controllers\KarirController;
use App\Http\Controllers\CsrController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LelangController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PpidController;
use App\Http\Controllers\WbsController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('beranda');
});

Route::get('/produk', function () {
    return view('produk');
});

// Berita: Route -> Controller -> Model (database) -> View
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{slug}', [BeritaController::class, 'show']);

// Lelang & AYDA: pola yang sama dengan berita
Route::get('/lelang', [LelangController::class, 'index']);
Route::get('/lelang/{slug}', [LelangController::class, 'show']);

// Program CSR: daftar dan detail
Route::get('/csr', [CsrController::class, 'index']);
Route::get('/csr/{slug}', [CsrController::class, 'show']);

// Profil perusahaan
Route::get('/profil-perusahaan', [ProfilController::class, 'index']);

// Laporan keuangan: tab triwulan dan tahunan
Route::get('/laporan', [LaporanController::class, 'index']);

// Karir: daftar lowongan dan detail
Route::get('/karir', [KarirController::class, 'index']);
Route::get('/karir/{slug}', [KarirController::class, 'show']);

// Pengaduan: GET menampilkan form, POST menerima kiriman (maksimal 5 kali per menit per pengunjung)
Route::get('/pengaduan', [PengaduanController::class, 'create']);
Route::post('/pengaduan', [PengaduanController::class, 'store'])->middleware('throttle:5,1');

// Unduh lampiran pengaduan: hanya untuk yang sudah login sebagai admin
Route::get('/lampiran-pengaduan/{pengaduan}', [PengaduanController::class, 'lampiran'])
    ->middleware('auth')
    ->name('pengaduan.lampiran');

// Whistleblowing System: GET menampilkan halaman, POST menerima laporan (maksimal 5 kali per menit per pengunjung)
Route::get('/wbs', [WbsController::class, 'create']);
Route::post('/wbs', [WbsController::class, 'store'])->middleware('throttle:5,1');

// Unduh lampiran laporan WBS: hanya untuk yang sudah login sebagai admin
Route::get('/lampiran-wbs/{laporan}', [WbsController::class, 'lampiran'])
    ->middleware('auth')
    ->name('wbs.lampiran');

// PPID: halaman utama, daftar informasi publik, dan permohonan informasi (maksimal 5 kali per menit per pengunjung)
Route::get('/ppid', [PpidController::class, 'index']);
Route::get('/ppid/informasi', [PpidController::class, 'informasi']);
Route::get('/ppid/permohonan', [PpidController::class, 'permohonan']);
Route::post('/ppid/permohonan', [PpidController::class, 'kirimPermohonan'])->middleware('throttle:5,1');

// Pengunjung yang belum login diarahkan ke halaman login panel admin
Route::get('/login', fn () => redirect('/admin/login'))->name('login');
