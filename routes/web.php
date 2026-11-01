<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CartController;

// Route Login
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Protected (Harus Login)
Route::middleware('auth')->group(function () {
    // Dashboard & Home Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [ProductController::class, 'home'])->name('home');

    // Resource Routes
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);

    // Route Keranjang Belanja (Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Route Transaksi & Pesanan
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/my-orders', [SaleController::class, 'myOrders'])->name('sales.myOrders');
    Route::post('/sales/{sale}/request-cancel', [SaleController::class, 'requestCancel'])->name('sales.requestCancel');
    Route::post('/sales/{sale}/approve-cancel', [SaleController::class, 'approveCancel'])->name('sales.approveCancel');
});