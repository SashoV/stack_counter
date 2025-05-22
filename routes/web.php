<?php

use App\Http\Controllers\AdminCalculationController;
use App\Http\Controllers\BookStackCalculationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/calculations', [BookStackCalculationController::class, 'index'])->name('dashboard.calculations');
    Route::post('/dashboard/calculations', [BookStackCalculationController::class, 'store'])->name('dashboard.calculations.store');
    Route::get('/dashboard/calculations/{id}', [BookStackCalculationController::class, 'show'])->name('dashboard.calculations.show');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard/admin/calculations', [AdminCalculationController::class, 'index'])->name('dashboard.admin.calculations');
    Route::get('/dashboard/admin/calculations/{id}', [AdminCalculationController::class, 'show'])->name('dashboard.admin.calculations.show');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/calculations/{id}/export', [BookStackCalculationController::class, 'exportCsv'])
        ->name('calculations.export');
});

require __DIR__.'/auth.php';
