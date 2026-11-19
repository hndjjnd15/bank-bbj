<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\CsrController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\KarirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LelangController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PengajuanKreditController;
use App\Http\Controllers\PpidController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\WbsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index']);

Route::get('/produk', function () {
    return view('produk');
});

// FAQ: pertanyaan umum dari produk yang sudah terbit
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

// Berita: Route -> Controller -> Model (database) -> View
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{slug}', [BeritaController::class, 'show']);

// Produk
Route::get('/tabungan', [ProdukController::class, 'tabungan'])->name('tabungan.index');
Route::get('/tabungan/{slug}', [ProdukController::class, 'tabunganDetail'])->name('tabungan.show');
Route::get('/kredit', [ProdukController::class, 'kredit'])->name('kredit.index');
Route::get('/kredit/{slug}', [ProdukController::class, 'kreditDetail'])->name('kredit.show');
Route::get('/deposito', [ProdukController::class, 'deposito']);

// Lelang & AYDA: pola yang sama dengan berita
Route::get('/lelang', [LelangController::class, 'index']);
Route::get('/lelang/{slug}', [LelangController::class, 'show']);

// Jaringan kantor: daftar dan detail
Route::get('/jaringan-kantor', [KantorController::class, 'index']);
Route::get('/jaringan-kantor/{slug}', [KantorController::class, 'show']);

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

// Pengajuan kredit: form, kiriman, halaman sukses, dan unduhan dokumen untuk admin
Route::get('/pengajuan-kredit', [PengajuanKreditController::class, 'create'])->name('pengajuan-kredit.create');
Route::post('/pengajuan-kredit', [PengajuanKreditController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('pengajuan-kredit.store');
Route::get('/pengajuan-kredit/sukses/{tiket}', [PengajuanKreditController::class, 'sukses'])->name('pengajuan-kredit.sukses');

// Unduh dokumen pengajuan kredit: hanya untuk yang sudah login sebagai admin
Route::get('/berkas-pengajuan-kredit/{pengajuan}/{kunci}', [PengajuanKreditController::class, 'berkas'])
    ->middleware('auth')
    ->name('pengajuan-kredit.berkas');
Route::get('/berkas-pengajuan-kredit/{pengajuan}', [PengajuanKreditController::class, 'berkasZip'])
    ->middleware('auth')
    ->name('pengajuan-kredit.berkas-zip');

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
