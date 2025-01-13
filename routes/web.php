<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POS\CustomerController;
use App\Http\Controllers\POS\CategoryController;
use App\Http\Controllers\POS\ProductController;
use App\Http\Controllers\POS\SaleController;
use App\Http\Controllers\POS\TerminalController;
use App\Http\Controllers\DashboardController;
use Spatie\Permission\Middleware\PermissionMiddleware;

Route::get('/', function () {
    return view('welcome');
});

// Authentication & Dashboard
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS Routes
    Route::prefix('pos')->name('pos.')->group(function () {
        // Terminal/POS
        Route::get('/terminal', [TerminalController::class, 'index'])->name('terminal');
        Route::post('/terminal/add-to-cart', [TerminalController::class, 'addToCart'])->name('terminal.add-to-cart');
        Route::post('/terminal/remove-from-cart', [TerminalController::class, 'removeFromCart'])->name('terminal.remove-from-cart');
        Route::post('/terminal/update-cart', [TerminalController::class, 'updateCart'])->name('terminal.update-cart');
        Route::post('/terminal/clear-cart', [TerminalController::class, 'clearCart'])->name('terminal.clear-cart');
        Route::post('/terminal/process-sale', [TerminalController::class, 'processSale'])->name('terminal.process-sale');

        // Customer Management
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::get('/customers/{customer}/history', [CustomerController::class, 'history'])->name('customers.history');

        // Category Management
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Product Management
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');

        // Sales Management
        Route::prefix('sales')->name('sales.')->middleware([PermissionMiddleware::class.':view-sales'])->group(function () {
            Route::get('/', [SaleController::class, 'index'])->name('index');
            Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
            Route::get('/{sale}/receipt', [SaleController::class, 'receipt'])->name('receipt');
            Route::post('/{sale}/void', [SaleController::class, 'void'])
                ->middleware([PermissionMiddleware::class.':void-sales'])
                ->name('void');
            Route::get('/report', [SaleController::class, 'report'])
                ->middleware([PermissionMiddleware::class.':view-reports'])
                ->name('report');
        });
    });
});
