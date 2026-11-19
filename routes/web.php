<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// --- 1. ROUTE LOGIN & LOGOUT ---
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- 2. ROUTE YANG WAJIB LOGIN ---
Route::middleware(['auth'])->group(function () {

    // Halaman Utama
    Route::get('/', function () {
        return Auth::user()->role == 'admin' 
            ? redirect()->route('dashboard') 
            : redirect()->route('transaksi.index');
    });

    // Dashboard Admin
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Kategori & Produk
    Route::resource('kategori', KategoriController::class);
    Route::resource('produk', ProdukController::class);

    // --- FITUR TRANSAKSI KASIR (Sesuai View & Controller Terbaru) ---
    
    // 1. Tampilan Utama Kasir
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

    // 2. Tambah Barang ke Keranjang (Session)
    Route::post('transaksi/tambah', [TransaksiController::class, 'tambahKeranjang'])->name('transaksi.tambah-keranjang');

    // 3. Hapus Barang dari Keranjang (Session)
    Route::delete('transaksi/hapus/{id}', [TransaksiController::class, 'hapusKeranjang'])->name('transaksi.hapus-keranjang');

    // 4. Proses Bayar Final (Simpan ke Database)
    Route::post('transaksi/bayar', [TransaksiController::class, 'store'])->name('transaksi.store');

    // 5. Tampilan Struk Belanja
    Route::get('transaksi/struk', [TransaksiController::class, 'show'])->name('transaksi.show');

    // --- FITUR LAPORAN ---
    Route::get('laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');

});