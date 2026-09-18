<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ValidityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('medicines', MedicineController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('stock', StockController::class)->only(['index']);
    Route::resource('movimentacoes', StockMovementController::class)->only(['index', 'store']);
    Route::resource('fornecedores', SupplierController::class)->parameters(['fornecedores' => 'supplier'])->only(['index', 'store', 'update', 'destroy']);
    Route::get('/validade', [ValidityController::class, 'index'])->name('validade.index');
});

require __DIR__.'/auth.php';
