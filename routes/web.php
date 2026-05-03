<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\VoidRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');

    // Barang detail (openable from dashboard search)
    Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');

    // Read routes for all authenticated users
    Route::resource('barang', BarangController::class)->only(['index']);
    Route::resource('barang-masuk', BarangMasukController::class)->only(['index']);
    Route::resource('barang-keluar', BarangKeluarController::class)->only(['index']);

    // Full access for owner
    Route::middleware('owner')->group(function () {
        Route::resource('barang', BarangController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('barang-masuk', BarangMasukController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('barang-keluar', BarangKeluarController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::get('/void-requests', [VoidRequestController::class, 'index'])->name('void-requests.index');
        Route::get('/stok-opname-harian/rekap', [StokOpnameController::class, 'rekap'])->name('stok-opname.rekap');
        Route::get('/stok-opname-harian/rekap/pdf', [StokOpnameController::class, 'exportPdf'])->name('stok-opname.export-pdf');

        Route::post('barang-masuk/{barangMasuk}/approve-void', [BarangMasukController::class, 'approveVoid'])->name('barang-masuk.approve-void');
        Route::post('barang-keluar/{barangKeluar}/approve-void', [BarangKeluarController::class, 'approveVoid'])->name('barang-keluar.approve-void');
    });

    // Insert-only access for karyawan
    Route::middleware('karyawan')->group(function () {
        Route::resource('barang', BarangController::class)->only(['create', 'store']);
        Route::resource('barang-masuk', BarangMasukController::class)->only(['create', 'store']);
        Route::resource('barang-keluar', BarangKeluarController::class)->only(['create', 'store']);

        Route::post('barang-masuk/{barangMasuk}/request-void', [BarangMasukController::class, 'requestVoid'])->name('barang-masuk.request-void');
        Route::post('barang-keluar/{barangKeluar}/request-void', [BarangKeluarController::class, 'requestVoid'])->name('barang-keluar.request-void');

        Route::get('/stok-opname-harian', [StokOpnameController::class, 'index'])->name('stok-opname.index');
        Route::post('/stok-opname-harian/pagi', [StokOpnameController::class, 'storeBerangkat'])->name('stok-opname.store-berangkat');
        Route::post('/stok-opname-harian/malam', [StokOpnameController::class, 'storeSisa'])->name('stok-opname.store-sisa');
        // Page per cabang: shows input pagi + input malam for a selected cabang
        Route::get('/stok-opname-harian/cabang/{cabang}', [StokOpnameController::class, 'showCabang'])->name('stok-opname.cabang');
    });

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

require __DIR__.'/auth.php';

