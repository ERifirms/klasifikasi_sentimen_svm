<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataLatihController;
use App\Http\Controllers\DataUjiController;
use App\Http\Controllers\KlasifikasiSVM;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return redirect('/login');
});

// Dashboard Route
Route::get('/dashboard',  [DashboardController::class, 'resultData'])->middleware(['auth', 'verified'])->name('dashboard');


// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Data Routes
    Route::prefix('')->group(function () {

        // ======= Data Latih start =======
        Route::get('/latih', [DataLatihController::class, 'index'])->name('pages.data.latih');
        Route::post('/data-latih/upload', [DataLatihController::class, 'upload'])->name('data-latih.upload');

        Route::post('/add-latih-data', [DataLatihController::class, 'store'])->name('addlatih-data.store');
        Route::put('/data-latih/update', [DataLatihController::class, 'update'])->name('updatelatih-data');
        Route::delete('/data-latih/{id}', [DataLatihController::class, 'destroy'])->name('data_latih.destroy');
        // ======= Data Latih end =======

        // ======= Data Uji Start =======
        Route::get('/uji', [DataUjiController::class, 'index'])->name('pages.data.uji');
        Route::post('/data-uji/upload', [DataUjiController::class, 'upload'])->name('data_uji.upload');
        Route::post('/add-data', [DataUjiController::class, 'store'])->name('add-data.store');
        Route::put('/data-uji/update', [DataUjiController::class, 'update'])->name('updateuji-data');
        Route::delete('/data-uji/{id}', [DataUjiController::class, 'destroy'])->name('data_uji.destroy');
        Route::post('/hasil-preprocessing', [PreprocessingController::class, 'preprocessAndTfidf'])->name('prepocessing-tfidf.preprocessAndTfidf');

        // ======= Data Uji end =======


        // ======= Data hasil prepocessing start =======
        Route::get('/hasil-preprocessing', [PreprocessingController::class, 'showHasilPreprocessing'])->name('pages.data.proses');
        // ======= Data hasil prepocessing start =======
    });

    // Klasifikasi Route
    Route::get('/klasifikasi', [KlasifikasiSVM::class, 'index'])->name('klasifikasi');
    Route::post('/hasil-klasifikasi', [KlasifikasiSVM::class, 'klasifikasiSVM'])->name('klasifikasi_svm');

    // Hasil Analisis Routes
    Route::prefix('')->group(function () {
        Route::get('/hasil-klasifikasi', [KlasifikasiSVM::class, 'getKlasifikasiSVM'])->name('pages.analisis.hasil-klasifikasi');
        Route::get('/tf-idf', [PreprocessingController::class, 'lihatTFIDF'])->name('pages.analisis.tfidf');
        Route::get('/wordcloud', [KlasifikasiSVM::class, 'getWordCloud'])->name('pages.analisis.wordcloud');
    });
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


require __DIR__ . '/auth.php';
