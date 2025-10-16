<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FertilizerController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\FarmerSubmissionController;
use App\Http\Controllers\KepalaDesaController;
use App\Http\Controllers\KepalaDesaDashboardController;
use App\Http\Controllers\KepalaDesaReportController;

// Hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('signin', [CustomAuthController::class, 'index'])->name('signin');
    Route::post('custom-login', [CustomAuthController::class, 'customSignin'])->name('signin.custom');

    Route::get('register', [CustomAuthController::class, 'registration'])->name('register');
    Route::post('custom-register', [CustomAuthController::class, 'customRegister'])->name('register.custom');
});

// Hanya untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('index', [CustomAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');
});

Route::get('/', function () {
    return view('dashboard.admin-desa');
})->name('index')->middleware(['auth', 'redirect.dashboard']);

Route::get('/index', function () {
    return view('dashboard.admin-desa');
})->name('index')->middleware(['auth', 'redirect.dashboard']);

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/admin-desa', [DashboardController::class, 'adminDesa'])
        ->middleware(['auth', 'role:admin_desa'])
        ->name('admin-desa');

    Route::get('/admin-koperasi', [DashboardController::class, 'adminKoperasi'])
        ->middleware(['auth', 'role:admin_koperasi'])
        ->name('admin-koperasi');

    Route::get('/kasir-koperasi', [DashboardController::class, 'kasirKoperasi'])
        ->middleware(['auth', 'role:kasir_koperasi'])
        ->name('kasir-koperasi');

    Route::get('/kepala-desa', [KepalaDesaDashboardController::class, 'dashboard'])
        ->middleware(['auth', 'role:kepala_desa'])
        ->name('kepala-desa');
});

// Routes untuk Admin Koperasi
Route::middleware(['auth', 'role:admin_koperasi'])->group(function () {
    Route::resource('petani', FarmerController::class)->names('farmers');
    Route::resource('pupuk', FertilizerController::class)->names('fertilizers')->except(['create'])->parameters(['pupuk' => 'fertilizer']);

    Route::get('/tambah-pupuk', function () {
        return view('fertilizers.add-fertilizer');
    })->name('fertilizers.create');

    Route::get('/stok-pupuk', [FertilizerController::class, 'stock'])
        ->name('fertilizers.stock');

    Route::post('/simpan-stok-pupuk', [FertilizerController::class, 'updateStockIn'])
        ->name('fertilizers.stock.store');

    Route::get('/subsidi-pupuk', [FertilizerController::class, 'stockSubsidies'])
        ->name('fertilizers.stock-subsidies');

    Route::post('/simpan-subsidi-pupuk', [FertilizerController::class, 'updateStockSubsidy'])
        ->name('fertilizers.stock-subsidy.store');
});

// Routes untuk Admin Desa dan Admin Koperasi
Route::middleware(['auth', 'role:admin_desa,admin_koperasi'])->group(function () {
    Route::get('/farmers-submissions', [FarmerController::class, 'submissions'])->name('farmers.submissions');

    Route::resource('farmer-submissions', FarmerSubmissionController::class);
    Route::post('/farmer-submissions/{farmerSubmission}/validate', [FarmerSubmissionController::class, 'validate'])
        ->name('farmer-submissions.validate');
});

// Routes untuk Admin Koperasi dan Kasir Koperasi
Route::middleware(['auth', 'role:admin_koperasi,kasir_koperasi'])->group(function () {
    Route::resource('transaksi', TransactionController::class)->names('transactions')
        ->except(['create']);

    Route::get('/ajax/farmers', [FarmerController::class, 'ajaxSearch'])
        ->name('farmers.ajax');

    Route::get('/ajax/fertilizers', [FertilizerController::class, 'fertilizersAjaxSearch'])
        ->name('fertilizers.ajax');

    Route::get('/ajax/allocations', [FertilizerController::class, 'allocationsAjaxSearch'])
        ->name('allocations.ajax');


    Route::get('/transaksi/{transaction}/cetak-struk', [TransactionController::class, 'printReceipt'])
        ->name('transactions.print');
});

// ========== ROUTE BARU UNTUK KEPALA DESA ==========
Route::middleware(['auth', 'role:kepala_desa'])->prefix('kepala-desa')->name('kepala-desa.')->group(function () {
    // Dashboard Kepala Desa
    Route::get('/dashboard', [KepalaDesaController::class, 'dashboard'])->name('dashboard');

    // Data Petani (VIEW ONLY)
    Route::prefix('petani')->name('petani.')->group(function () {
        Route::get('validated', [KepalaDesaController::class, 'petaniValidated'])->name('validated');
        Route::get('pending', [KepalaDesaController::class, 'petaniPending'])->name('pending');
        Route::get('rejected', [KepalaDesaController::class, 'petaniRejected'])->name('rejected');
        Route::get('{id}', [KepalaDesaController::class, 'showPetani'])->name('show');
        Route::get('submission/{id}', [KepalaDesaController::class, 'showSubmission'])->name('submission.show');
    });
});

// Dashboard routes
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/admin-desa', [DashboardController::class, 'adminDesa'])
        ->middleware(['auth', 'role:admin_desa'])
        ->name('admin-desa');

    Route::get('/admin-koperasi', [DashboardController::class, 'adminKoperasi'])
        ->middleware(['auth', 'role:admin_koperasi'])
        ->name('admin-koperasi');

    Route::get('/kasir-koperasi', [DashboardController::class, 'kasirKoperasi'])
        ->middleware(['auth', 'role:kasir_koperasi'])
        ->name('kasir-koperasi');

    // Ganti dengan controller baru
    Route::get('/kepala-desa', [KepalaDesaDashboardController::class, 'dashboard'])
        ->middleware(['auth', 'role:kepala_desa'])
        ->name('kepala-desa');
});

// Route untuk Kepala Desa (di bagian Kepala Desa)
Route::middleware(['auth', 'role:kepala_desa'])->prefix('kepala-desa')->name('kepala-desa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [KepalaDesaDashboardController::class, 'dashboard'])->name('dashboard');


    // Data Petani (VIEW ONLY)
    Route::prefix('petani')->name('petani.')->group(function () {
        Route::get('validated', [KepalaDesaController::class, 'petaniValidated'])->name('validated');
        Route::get('pending', [KepalaDesaController::class, 'petaniPending'])->name('pending');
        Route::get('rejected', [KepalaDesaController::class, 'petaniRejected'])->name('rejected');
        Route::get('{id}', [KepalaDesaController::class, 'showPetani'])->name('show');
        Route::get('submission/{id}', [KepalaDesaController::class, 'showSubmission'])->name('submission.show');
    });

    // Laporan-laporan - PASTIKAN INI ADA
    Route::prefix('laporan')->name('reports.')->group(function () {
        // Laporan Pergerakan Pupuk
        Route::get('pergerakan-pupuk', [KepalaDesaReportController::class, 'fertilizerMovement'])
            ->name('fertilizer-movement');
        Route::get('pergerakan-pupuk/export', [KepalaDesaReportController::class, 'exportFertilizerMovement'])
            ->name('fertilizer-movement.export');

        // Laporan Alokasi Subsidi
        Route::get('alokasi-subsidi', [KepalaDesaReportController::class, 'subsidyAllocation'])
            ->name('subsidy-allocation');
        Route::get('alokasi-subsidi/export', [KepalaDesaReportController::class, 'exportSubsidyAllocation'])
            ->name('subsidy-allocation.export');

        // Laporan Keuangan
        Route::get('keuangan', [KepalaDesaReportController::class, 'financial'])
            ->name('financial');
        Route::get('keuangan/export', [KepalaDesaReportController::class, 'exportFinancial'])
            ->name('financial.export');
    });
});