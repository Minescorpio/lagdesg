<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoyaltyProgramController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Pos\Terminal;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    
    // POS Terminal
    Route::get('/pos', Terminal::class)->name('pos.terminal');
    
    // Settings
    Route::get('/settings', function() {
        return view('settings.index');
    })->name('settings');
});

// Products Routes
Route::prefix('products')->group(function () {
    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
});
Route::resource('products', ProductController::class);

// Categories Routes
Route::prefix('categories')->group(function () {
    Route::post('/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
});
Route::resource('categories', CategoryController::class);

// Customers Routes
Route::prefix('customers')->group(function () {
    Route::get('/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::post('/{customer}/loyalty-points', [CustomerController::class, 'addLoyaltyPoints'])
        ->name('customers.add-loyalty-points');
    Route::post('/{customer}/credit', [CustomerController::class, 'addCredit'])
        ->name('customers.add-credit');
});
Route::resource('customers', CustomerController::class);

// Loyalty Programs Routes
Route::prefix('loyalty-programs')->group(function () {
    Route::post('/{loyaltyProgram}/calculate-reward', [LoyaltyProgramController::class, 'calculateReward'])
        ->name('loyalty-programs.calculate-reward');
});
Route::resource('loyalty-programs', LoyaltyProgramController::class);

// Stock Routes
Route::prefix('stock')->group(function () {
    Route::get('/low-stock', [StockController::class, 'lowStock'])->name('stock.low');
    Route::get('/history/{product}', [StockController::class, 'stockHistory'])->name('stock.history');
    Route::post('/bulk-adjustment', [StockController::class, 'bulkAdjustment'])->name('stock.bulk-adjustment');
    Route::get('/export', [StockController::class, 'export'])->name('stock.export');
});
Route::resource('stock', StockController::class);

// Sales Routes
Route::prefix('sales')->group(function () {
    Route::post('/{sale}/void', [SaleController::class, 'void'])->name('sales.void');
    Route::get('/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
});
Route::resource('sales', SaleController::class);

// Error Pages
Route::fallback(function () {
    return view('errors.404');
});
