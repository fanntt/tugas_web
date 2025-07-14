<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\IrfanProduct;

// Home page
Route::get('/', function () {
    $products = IrfanProduct::all()->unique('name');
    return view('welcome', compact('products'));
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard routes (protected by auth middleware, admin check in route)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->index();
    })->name('admin.dashboard');
    // Categories
    Route::get('/categories', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->categories();
    })->name('admin.categories.index');
    Route::get('/categories/create', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->createCategory();
    })->name('admin.categories.create');
    Route::post('/categories', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->storeCategory(request());
    })->name('admin.categories.store');
    Route::get('/categories/{category}/edit', function ($category) {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $categoryModel = \App\Models\IrfanCategory::findOrFail($category);
        return app(\App\Http\Controllers\DashboardController::class)->editCategory($categoryModel);
    })->name('admin.categories.edit');
    Route::put('/categories/{category}', function ($category) {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $categoryModel = \App\Models\IrfanCategory::findOrFail($category);
        return app(\App\Http\Controllers\DashboardController::class)->updateCategory(request(), $categoryModel);
    })->name('admin.categories.update');
    Route::delete('/categories/{category}', function ($category) {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $categoryModel = \App\Models\IrfanCategory::findOrFail($category);
        return app(\App\Http\Controllers\DashboardController::class)->deleteCategory($categoryModel);
    })->name('admin.categories.delete');
    // Products
    Route::get('/products', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->products();
    })->name('admin.products.index');
    Route::get('/products/create', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->createProduct();
    })->name('admin.products.create');
    Route::post('/products', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->storeProduct(request());
    })->name('admin.products.store');
    Route::get('/products/{product}/edit', function ($product) {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $productModel = \App\Models\IrfanProduct::findOrFail($product);
        return app(\App\Http\Controllers\DashboardController::class)->editProduct($productModel);
    })->name('admin.products.edit');
    Route::put('/products/{product}', function ($product) {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $productModel = \App\Models\IrfanProduct::findOrFail($product);
        return app(\App\Http\Controllers\DashboardController::class)->updateProduct(request(), $productModel);
    })->name('admin.products.update');
    Route::delete('/products/{product}', function ($product) {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $productModel = \App\Models\IrfanProduct::findOrFail($product);
        return app(\App\Http\Controllers\DashboardController::class)->deleteProduct($productModel);
    })->name('admin.products.delete');
    Route::get('/products/{product}', function ($product) {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }
        $productModel = \App\Models\IrfanProduct::findOrFail($product);
        return app(\App\Http\Controllers\DashboardController::class)->showProduct($productModel);
    })->name('admin.products.show');
    // Orders (admin)
});

// User routes (protected by auth middleware, user check in route)
Route::middleware(['auth'])->group(function () {
    // Orders (shared for user and admin)
    Route::get('/orders', function () {
        if (Auth::user()->role === 'admin') {
            return app(\App\Http\Controllers\DashboardController::class)->adminOrders();
        } elseif (Auth::user()->role === 'user') {
            return app(\App\Http\Controllers\DashboardController::class)->orders();
        } else {
            abort(403, 'Unauthorized.');
        }
    })->name('orders.index');

    // Create Order
    Route::get('/orders/create', function () {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->createOrder();
    })->name('orders.create');
    Route::post('/orders', function () {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->storeOrder(request());
    })->name('orders.store');

    // Show Order
    Route::get('/orders/{order}', function ($order) {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        $orderModel = \App\Models\IrfanOrder::findOrFail($order);
        return app(\App\Http\Controllers\DashboardController::class)->showOrder($orderModel);
    })->name('orders.show');

    // Edit Order
    Route::get('/orders/{order}/edit', function ($order) {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        $orderModel = \App\Models\IrfanOrder::findOrFail($order);
        return app(\App\Http\Controllers\DashboardController::class)->editOrder($orderModel);
    })->name('orders.edit');
    Route::put('/orders/{order}', function ($order) {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        $orderModel = \App\Models\IrfanOrder::findOrFail($order);
        return app(\App\Http\Controllers\DashboardController::class)->updateOrder(request(), $orderModel);
    })->name('orders.update');

    // Delete Order
    Route::delete('/orders/{order}', function ($order) {
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized.');
        }
        $orderModel = \App\Models\IrfanOrder::findOrFail($order);
        return app(\App\Http\Controllers\DashboardController::class)->deleteOrder($orderModel);
    })->name('orders.delete');

    // My Orders (khusus user)
    Route::get('/my-orders', function () {
        if (Auth::user()->role !== 'user') {
            abort(403, 'Unauthorized.');
        }
        return app(\App\Http\Controllers\DashboardController::class)->myOrders();
    })->name('orders.my-orders');

    // Tambahkan route dashboard user (hanya produk, tanpa welcome message)
    Route::get('/products-user', function () {
        if (Auth::user()->role !== 'user') {
            abort(403, 'Unauthorized.');
        }
        $products = \App\Models\IrfanProduct::all();
        return view('dashboard.products.user-index', compact('products'));
    })->name('user.products.index');

    // Tambahkan route detail produk user
    Route::get('/products-user/{product}', function ($product) {
        if (Auth::user()->role !== 'user') {
            abort(403, 'Unauthorized.');
        }
        $productModel = \App\Models\IrfanProduct::findOrFail($product);
        $productModel->load('category');
        return view('dashboard.products.user-show', compact('productModel'));
    })->name('user.products.show');
});
