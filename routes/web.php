<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PaymentRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
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

Route::get('/pagamentos/{reference}', [PaymentRequestController::class, 'show'])->name('pagamentos.show');
Route::post('/pagamentos/{reference}/confirmar', [PaymentRequestController::class, 'confirm'])->name('pagamentos.confirm');
Route::get('/pagamentos/{reference}/status', [PaymentRequestController::class, 'status'])->name('pagamentos.status');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('medicines', MedicineController::class)->only(['index']);
    Route::resource('stock', StockController::class)->only(['index']);
    Route::resource('movimentacoes', StockMovementController::class)->only(['index', 'store']);
    Route::resource('vendas', SaleController::class)->parameters(['vendas' => 'sale'])->only(['index', 'create', 'store', 'show']);
    Route::get('/validade', [ValidityController::class, 'index'])->name('validade.index');
    Route::post('/pagamentos', [PaymentRequestController::class, 'store'])->name('pagamentos.store');

    Route::middleware('admin')->group(function () {
        Route::resource('medicines', MedicineController::class)->only(['store', 'update', 'destroy']);
        Route::resource('fornecedores', SupplierController::class)->parameters(['fornecedores' => 'supplier'])->only(['index', 'store', 'update', 'destroy']);
        Route::get('/relatorios', [ReportController::class, 'index'])->name('relatorios.index');
    });
});

require __DIR__.'/auth.php';
