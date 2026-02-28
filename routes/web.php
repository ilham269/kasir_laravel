<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

// Route tanpa parameter
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);

Route::middleware('auth')->group(function () {
    Route::get('cashier', [CashierController::class, 'index'])->name('cashier.index');
    Route::post('cashier/add', [CashierController::class, 'addByBarcode'])->name('cashier.add');
    Route::post('cashier/add-product', [CashierController::class, 'addByProduct'])->name('cashier.add-product');
    Route::delete('cashier/{productId}', [CashierController::class, 'removeItem'])->name('cashier.remove');
    Route::post('cashier/pay', [CashierController::class, 'pay'])->name('cashier.pay');
    Route::get('cashier/transactions/{transaction}', [CashierController::class, 'showTransaction'])->name('cashier.transactions.show');
    Route::get('cashier/transactions/{transaction}/receipt', [CashierController::class, 'showReceipt'])->name('cashier.transactions.receipt');
    Route::get('cashier/transactions/{transaction}/receipt/export', [CashierController::class, 'exportReceipt'])->name('cashier.transactions.receipt.export');
});


Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
