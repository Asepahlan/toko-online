<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Auth;

// ===== ROUTE PUBLIK (TANPA AUTH) =====
// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/new', [ProductController::class, 'new'])->name('products.new');
Route::get('/products/popular', [ProductController::class, 'popular'])->name('products.popular');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/category/{category}', [ProductController::class, 'category'])->name('products.category');

// Kategori
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// ===== RUTE CART (PUBLIK/AJAX) =====
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::put('/update/{id}', [CartController::class, 'update']);
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::get('/total', [CartController::class, 'getTotal'])->name('total');
});

// ===== ROUTE YANG MEMERLUKAN AUTH =====
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Order & Checkout
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/resend-confirmation', [OrderController::class, 'resendConfirmation'])
         ->name('orders.resend.confirmation');
});

// ===== ADMIN ROUTES =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index']);
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'getStats'])->name('dashboard.stats');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Products
    Route::resource('products', AdminProductController::class);
    Route::patch('/products/{product}/update-status', [AdminProductController::class, 'updateStatus'])
        ->name('products.update-status');

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Orders
    Route::get('/orders/filter', [AdminOrderController::class, 'filter'])->name('orders.filter');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
    Route::resource('orders', AdminOrderController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/sales', [ReportController::class, 'exportSalesReport'])->name('reports.sales');
});

Auth::routes();
