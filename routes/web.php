<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisLapanganController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\LaporanRekapitulasiController;
use App\Http\Controllers\DashboardController;


// =====================
// REDIRECT ROOT PAGE
// =====================
Route::get('/', function () {
    return view('welcome');
});

// =====================
// AUTH
// =====================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =====================
// PUBLIK (tanpa login)
// =====================
Route::get('/beranda', function () {
    return view('pelanggan.berandaPage');
});

Route::get('/lapangan', [LapanganController::class, 'lapanganPage'])
    ->name('lapangan.public');

Route::get('/kebijakan-privasi', function () {
    return view('pelanggan.kebijakan');
})->name('kebijakan');

Route::get('/syarat-ketentuan', function () {
    return view('pelanggan.syarat');
})->name('syarat');

Route::get('/jadwal', [LapanganController::class, 'public'])->name('jadwal.public');
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/cek-status', [BookingController::class, 'cek'])->name('booking.cek');
Route::post('/cek-status', [BookingController::class, 'cekStatus'])->name('booking.cekStatus');

// Pembayaran (publik karena pelanggan ga login)
Route::get('/pembayaran/{bookingId}', [PembayaranController::class, 'show'])->name('pembayaran.show');
Route::post('/pembayaran/{bookingId}/proses', [PembayaranController::class, 'proses'])->name('pembayaran.proses');
Route::get('/pembayaran/{bookingId}/sukses', [PembayaranController::class, 'sukses'])->name('pembayaran.sukses');
Route::get('/pembayaran/{bookingId}/simulasi', [PembayaranController::class, 'simulasi'])->name('pembayaran.simulasi');
Route::post('/pembayaran/{bookingId}/konfirmasi', [PembayaranController::class, 'konfirmasiManual'])->name('pembayaran.konfirmasi');

// =====================
// ADMIN (wajib login)
// =====================
Route::middleware(['auth.admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Jenis Lapangan
    Route::resource('jenisLapangan', JenisLapanganController::class);

    // Lapangan
    Route::resource('lapangan', LapanganController::class);

    // Jadwal
    Route::resource('jadwal', JadwalController::class);

    // Promo
    Route::resource('promo', PromoController::class);

    // Booking (admin hanya lihat)
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');

    // Laporan
    Route::get('/laporan', [LaporanRekapitulasiController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [LaporanRekapitulasiController::class, 'exportPdf'])->name('laporan.exportPdf');
});

// =====================
// SHORTCUT DASHBOARD
// =====================
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

