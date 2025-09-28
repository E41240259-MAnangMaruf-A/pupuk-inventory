<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FertilizerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\FarmerSubmissionController;
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
})->middleware('redirect.dashboard');

Route::get('/index', function () {
    return view('dashboard.admin-desa');
})->name('index')->middleware('redirect.dashboard');


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

// Routes untuk Data Petani (Admin)
Route::resource('farmers', FarmerController::class);
Route::get('/farmers-submissions', [FarmerController::class, 'submissions'])->name('farmers.submissions');

// Routes untuk Data Petani Desa (Pengajuan)
Route::resource('farmer-submissions', FarmerSubmissionController::class);
Route::post('/farmer-submissions/{farmerSubmission}/validate', [FarmerSubmissionController::class, 'validate'])
    ->name('farmer-submissions.validate');