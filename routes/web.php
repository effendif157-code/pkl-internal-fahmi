<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;

// Admin Controllers
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CartController;

// Customer Controllers
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;

// Auth & Payment Controllers
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================================================
// HALAMAN PUBLIK (Tanpa Login)
// ================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']); // Alias untuk redirect default Laravel

// Katalog Produk
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// ================================================
// HALAMAN YANG BUTUH LOGIN (Customer)
// ================================================
Route::middleware('auth')->group(function () {

    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout & Order Customer
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Profil User
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

// ================================================
// HALAMAN ADMIN (Butuh Login + Role Admin)
// ================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produk CRUD (Destroy diarahkan ke DashboardController sesuai permintaan Anda)
    Route::resource('products', AdminProductController::class)->except(['destroy']);
    Route::delete('/products/{product}', [DashboardController::class, 'destroy'])->name('products.destroy');

    // Resource Categories & Users
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('users', UserController::class);

    // Manajemen Pesanan Admin
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    Route::patch('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Laporan & Export (Dipindahkan ke dalam group Admin agar aman)
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/export-sales', [ReportController::class, 'exportSales'])->name('reports.export-sales');
});

// ================================================
// AUTH & OAUTH
// ================================================
Auth::routes();

// Google Login
Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google', 'redirect')->name('auth.google');
    Route::get('/auth/google/callback', 'callback')->name('auth.google.callback');
});

// Webhook Midtrans
Route::post('midtrans/notification', [MidtransNotificationController::class, 'handle'])->name('midtrans.notification');
// Tambahkan ini di dalam group middleware auth
Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');
Route::middleware('auth')->group(function () {
    // ... rute lainnya ...

    // Rute untuk menangani pengalihan setelah pembayaran Midtrans
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/orders/{order}/pending', [OrderController::class, 'pending'])->name('orders.pending');
});
Route::middleware('auth')->group(function () {
    // Gunakan parameter order agar ID pesanan (seperti '6') bisa ditangkap
    Route::get('/orders/success/{order}', [OrderController::class, 'success'])->name('orders.success');
});
// Pastikan rute ini berada di dalam group middleware 'auth'
Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');
Route::middleware('auth')->group(function () {
    Route::get('/orders/pending/{order}', [OrderController::class, 'pending'])->name('orders.pending');
});