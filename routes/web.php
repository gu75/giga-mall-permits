<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialPermitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\WorkPermitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome_custom');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::view('/terms', 'terms')->name('terms');
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tenant Management (Generates index, create, store, edit, update, destroy)
    Route::middleware('role:operations')->group(function () {
        Route::resource('tenants', TenantController::class);
    });

    // --- Work Permits ---
    Route::get('/work-permits', [WorkPermitController::class, 'index'])->name('work-permits.index');

    Route::middleware('role:tenant')->group(function () {
        Route::get('/work-permits-create', [WorkPermitController::class, 'create'])->name('work-permits.create');
        Route::post('/work-permits', [WorkPermitController::class, 'store'])->name('work-permits.store');
    });

    // Admin & Operations edit access
    Route::middleware('role:admin,operations')->group(function () {
        Route::get('/work-permits/{workPermit}/edit', [WorkPermitController::class, 'edit'])->name('work-permits.edit');
        Route::put('/work-permits/{workPermit}', [WorkPermitController::class, 'update'])->name('work-permits.update');
    });

    Route::get('/work-permits/{workPermit}', [WorkPermitController::class, 'show'])->name('work-permits.show');
    Route::get('/work-permits/{workPermit}/pdf', [WorkPermitController::class, 'pdf'])->name('work-permits.pdf');

    Route::middleware('role:operations,hse,security')->group(function () {
        Route::post('/work-permits/{workPermit}/approve', [WorkPermitController::class, 'approve'])->name('work-permits.approve');
        Route::post('/work-permits/{workPermit}/reject', [WorkPermitController::class, 'reject'])->name('work-permits.reject');
    });

    // --- Material Inward/Outward Permits ---
    Route::get('/material-permits', [MaterialPermitController::class, 'index'])->name('material-permits.index');

    Route::middleware('role:tenant')->group(function () {
        Route::get('/material-permits-create', [MaterialPermitController::class, 'create'])->name('material-permits.create');
        Route::post('/material-permits', [MaterialPermitController::class, 'store'])->name('material-permits.store');
    });

    Route::get('/material-permits/{materialPermit}', [MaterialPermitController::class, 'show'])->name('material-permits.show');
    Route::get('/material-permits/{materialPermit}/pdf', [MaterialPermitController::class, 'pdf'])->name('material-permits.pdf');

    Route::middleware('role:operations')->group(function () {
        Route::post('/material-permits/{materialPermit}/approve', [MaterialPermitController::class, 'approve'])->name('material-permits.approve');
        Route::post('/material-permits/{materialPermit}/reject', [MaterialPermitController::class, 'reject'])->name('material-permits.reject');
    });

    Route::middleware('role:security')->group(function () {
        Route::post('/material-permits/{materialPermit}/log-gate', [MaterialPermitController::class, 'logGate'])->name('material-permits.log-gate');
    });
});

require __DIR__.'/auth.php';
