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

    Route::get('/kepala-desa', [DashboardController::class, 'kepalaDesa'])
        ->middleware(['auth', 'role:kepala_desa'])
        ->name('kepala-desa');
});

// ========== PERBAIKAN DI SINI ==========

// HAPUS route farmers untuk kepala desa yang lama (konflik)
// Route::middleware(['auth', 'role:admin_koperasi,kepala_desa'])->group(function () {
//     Route::resource('petani', FarmerController::class)->names('farmers');
// });

// GANTI dengan route terpisah
Route::middleware(['auth', 'role:admin_koperasi'])->group(function () {
    // Farmers untuk admin koperasi (bisa CRUD)
    Route::resource('petani', FarmerController::class)->names('farmers');
});

Route::middleware(['auth', 'role:admin_koperasi'])->group(function () {
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

Route::middleware(['auth', 'role:admin_desa,admin_koperasi'])->group(function () {
    Route::get('/farmers-submissions', [FarmerController::class, 'submissions'])->name('farmers.submissions');

    // Routes untuk Data Petani Desa (Pengajuan)
    Route::resource('farmer-submissions', FarmerSubmissionController::class);
    Route::post('/farmer-submissions/{farmerSubmission}/validate', [FarmerSubmissionController::class, 'validate'])
        ->name('farmer-submissions.validate');
});

// HAPUS route farmers untuk kepala desa yang lama (konflik)
// Route::middleware(['auth', 'role:kepala_desa'])->group(function () {
//     Route::resource('farmers', FarmerController::class);
// });

Route::middleware(['auth', 'role:admin_koperasi,kasir_koperasi'])->group(function () {
    Route::resource('transaksi', TransactionController::class)->names('transactions')
        ->except(['create']);

    Route::get('/ajax/farmers', [FarmerController::class, 'ajaxSearch'])
        ->name('farmers.ajax');

    Route::get('/ajax/fertilizers', [FertilizerController::class, 'ajaxSearch'])
        ->name('fertilizers.ajax');

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
    // Dashboard Kepala Desa
    Route::get('/dashboard', [KepalaDesaDashboardController::class, 'dashboard'])->name('dashboard');
    
    // Data Petani (VIEW ONLY)
    Route::prefix('petani')->name('petani.')->group(function () {
        Route::get('validated', [KepalaDesaController::class, 'petaniValidated'])->name('validated');
        Route::get('pending', [KepalaDesaController::class, 'petaniPending'])->name('pending');
        Route::get('rejected', [KepalaDesaController::class, 'petaniRejected'])->name('rejected');
        Route::get('{id}', [KepalaDesaController::class, 'showPetani'])->name('show');
        Route::get('submission/{id}', [KepalaDesaController::class, 'showSubmission'])->name('submission.show');
    });
});

