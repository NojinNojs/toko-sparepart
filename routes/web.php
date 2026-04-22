<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk/{produk}', [HomeController::class, 'show'])->name('produk.show');

// ── Authenticated Routes (Profile) ──────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Customer Routes (auth + role:customer) ───────────────────
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Rate limit checkout: 10 attempts per minute per user
    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('checkout.store');
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
});

// ── Admin Routes (auth + role:admin) ─────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // API Polling endpoint — dipakai Alpine.js untuk auto-refresh data dashboard
    Route::get('/dashboard/poll', [Admin\DashboardController::class, 'poll'])->name('dashboard.poll');

    Route::resource('produk', Admin\ProdukController::class);
    Route::resource('brand', Admin\BrandController::class)->except(['show']);
    Route::resource('kategori', Admin\KategoriController::class)->except(['show']);
    Route::get('pembelian', [Admin\PembelianController::class, 'index'])->name('pembelian.index');
    Route::get('pembelian/{pembelian}', [Admin\PembelianController::class, 'show'])->name('pembelian.show');
    Route::patch('pembelian/{pembelian}/status', [Admin\PembelianController::class, 'updateStatus'])
        ->name('pembelian.updateStatus');
});

require __DIR__.'/auth.php';
