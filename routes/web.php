<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganPeriodeController;
use App\Http\Controllers\SkmDetailController;
use App\Http\Controllers\SkmPreviewController;
use App\Http\Controllers\Admin\DashboardController; // ✅ TAMBAHAN (AMAN)
use App\Http\Controllers\Admin\DetailSkmController; // ✅ TAMBAHAN (AMAN)
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD (BREEZE)
|--------------------------------------------------------------------------
| Dashboard memuat data tabel dari controller (supaya $rows tidak error)
*/
Route::get('/dashboard', [RuanganPeriodeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| CRUD RUANGAN PERIODE + DETAIL + PREVIEW
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CRUD RUANGAN PERIODE (SKM PARENT)
    |--------------------------------------------------------------------------
    */
    Route::resource('ruangan-periode', RuanganPeriodeController::class)
        ->only(['index', 'create', 'store', 'destroy'])
        ->names('ruangan-periode');

    /*
    |--------------------------------------------------------------------------
    | PREVIEW SKM (parent + semua detail)
    |--------------------------------------------------------------------------
    | URL : /ruangan-periode/{ruangan_periode}/preview
    | Name: ruangan-periode.preview
    */
    Route::get(
        'ruangan-periode/{ruangan_periode}/preview',
        [SkmPreviewController::class, 'show']
    )->name('ruangan-periode.preview');

    /*
    |--------------------------------------------------------------------------
    | RESET TOTAL (hapus semua + reset ID)
    |--------------------------------------------------------------------------
    */
    Route::delete(
        'ruangan-periode/reset',
        [RuanganPeriodeController::class, 'reset']
    )->name('ruangan-periode.reset');

    /*
    |--------------------------------------------------------------------------
    | CRUD DETAIL (NESTED)
    |--------------------------------------------------------------------------
    */
    Route::prefix('ruangan-periode/{ruangan_periode}')
        ->name('ruangan-periode.detail.')
        ->group(function () {

            // INDEX
            Route::get(
                'detail',
                [SkmDetailController::class, 'index']
            )->name('index');

            // CREATE
            Route::get(
                'detail/create',
                [SkmDetailController::class, 'create']
            )->name('create');

            // STORE
            Route::post(
                'detail',
                [SkmDetailController::class, 'store']
            )->name('store');

            // EDIT (FORM)
            Route::get(
                'detail/{detail}/edit',
                [SkmDetailController::class, 'edit']
            )->name('edit');

            // UPDATE (PUT — TETAP ADA, JANGAN DIHAPUS)
            Route::put(
                'detail/{detail}',
                [SkmDetailController::class, 'update']
            )->name('update');

            // UPDATE (PATCH — TAMBAHAN UNTUK MODAL / INLINE EDIT)
            Route::patch(
                'detail/{detail}',
                [SkmDetailController::class, 'update']
            )->name('update.patch');

            // ✅ UNDO (REVERT) — TAMBAHAN (tidak merusak route lama)
            Route::post(
                'detail/{detail}/undo',
                [SkmDetailController::class, 'undo']
            )->name('undo');

            // DELETE
            Route::delete(
                'detail/{detail}',
                [SkmDetailController::class, 'destroy']
            )->name('destroy');
        });
});

/*
|--------------------------------------------------------------------------
| PROFILE (BREEZE)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN AREA (TAMBAHAN — AMAN)
|--------------------------------------------------------------------------
| HANYA ROLE ADMIN
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // ✅ FIX: route lama tetap ada, tapi diarahkan ke halaman yang benar
        Route::get('/detail', function () {
            return redirect()->route('admin.detail-skm.index');
        })->name('detail.index');

        Route::get('/hasil', function () {
            return view('admin.hasil.index');
        })->name('hasil.index');

        // ✅ Detail SKM (list)
        Route::get('/detail-skm', [DetailSkmController::class, 'index'])
            ->name('detail-skm.index');

        // ✅ Detail SKM (show halaman biasa)
        Route::get('/detail-skm/{ruangan_periode}', [DetailSkmController::class, 'show'])
            ->name('detail-skm.show');
    });

/*
|--------------------------------------------------------------------------
| AUTH (LOGIN, LOGOUT, DLL)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
