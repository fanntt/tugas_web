<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard routes (protected by auth middleware)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    // Categories
    Route::get('/categories', [DashboardController::class, 'categories'])->name('admin.categories.index');
    Route::get('/categories/create', [DashboardController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/categories', [DashboardController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [DashboardController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [DashboardController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [DashboardController::class, 'deleteCategory'])->name('admin.categories.delete');
    // Products
    Route::get('/products', [DashboardController::class, 'products'])->name('admin.products.index');
    Route::get('/products/create', [DashboardController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/products', [DashboardController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [DashboardController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/products/{product}', [DashboardController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{product}', [DashboardController::class, 'deleteProduct'])->name('admin.products.delete');
});

// User routes (protected by auth and is_user middleware)
Route::middleware(['auth', 'is_user'])->group(function () {
    // Orders (user only)
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders.index');
    Route::get('/orders/create', [DashboardController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [DashboardController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}', [DashboardController::class, 'showOrder'])->name('orders.show');
    Route::get('/orders/{order}/edit', [DashboardController::class, 'editOrder'])->name('orders.edit');
    Route::put('/orders/{order}', [DashboardController::class, 'updateOrder'])->name('orders.update');
    Route::delete('/orders/{order}', [DashboardController::class, 'deleteOrder'])->name('orders.delete');
    Route::get('/my-orders', [DashboardController::class, 'myOrders'])->name('orders.my-orders');
});
