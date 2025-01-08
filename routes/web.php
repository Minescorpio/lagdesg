<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoyaltyProgramController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PosController;
use App\Livewire\Dashboard\Index as DashboardComponent;
use App\Livewire\Pos\Terminal as PosTerminalComponent;
use App\Livewire\Categories\Index as CategoriesComponent;
use App\Livewire\Products\Index as ProductsComponent;
use App\Livewire\Customers\Index as CustomersComponent;
use App\Livewire\Sales\Index as SalesComponent;
use App\Livewire\Stock\Index as StockComponent;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard
    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');
    
    // Settings
    Route::get('/settings', function() {
        return view('settings.index');
    })->name('settings');

    // POS Routes
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/terminal', PosTerminalComponent::class)->name('terminal');
        Route::get('/products', [PosController::class, 'getProducts'])->name('products');
        Route::post('/add-to-cart', [PosController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/update-cart', [PosController::class, 'updateCart'])->name('update-cart');
        Route::post('/remove-from-cart', [PosController::class, 'removeFromCart'])->name('remove-from-cart');
        Route::post('/clear-cart', [PosController::class, 'clearCart'])->name('clear-cart');
        Route::post('/complete-sale', [PosController::class, 'completeSale'])->name('complete-sale');
        Route::get('/receipt/{sale}', [PosController::class, 'receipt'])->name('receipt');
    });

    // Categories Routes
    Route::get('/categories', CategoriesComponent::class)->name('categories.index');
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/create', \App\Livewire\Categories\Create::class)->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Products Routes
    Route::get('/products', ProductsComponent::class)->name('products.index');
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        Route::get('/create', \App\Livewire\Products\Create::class)->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });

    // Customers Routes
    Route::get('/customers', CustomersComponent::class)->name('customers.index');
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/search', [CustomerController::class, 'search'])->name('search');
        Route::get('/create', \App\Livewire\Customers\Create::class)->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::post('/{customer}/loyalty-points', [CustomerController::class, 'addLoyaltyPoints'])->name('add-loyalty-points');
        Route::post('/{customer}/credit', [CustomerController::class, 'addCredit'])->name('add-credit');
    });

    // Sales Routes
    Route::get('/sales', SalesComponent::class)->name('sales.index');
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/create', \App\Livewire\Sales\Create::class)->name('create');
        Route::get('/{sale}/receipt', [SaleController::class, 'receipt'])->name('receipt');
        Route::post('/{sale}/void', [SaleController::class, 'void'])->name('void');
    });

    // Loyalty Programs Routes
    Route::prefix('loyalty-programs')->name('loyalty-programs.')->group(function () {
        Route::post('/{loyaltyProgram}/calculate-reward', [LoyaltyProgramController::class, 'calculateReward'])->name('calculate-reward');
        Route::get('/', [LoyaltyProgramController::class, 'index'])->name('index');
        Route::get('/create', [LoyaltyProgramController::class, 'create'])->name('create');
        Route::post('/', [LoyaltyProgramController::class, 'store'])->name('store');
        Route::get('/{loyaltyProgram}/edit', [LoyaltyProgramController::class, 'edit'])->name('edit');
        Route::put('/{loyaltyProgram}', [LoyaltyProgramController::class, 'update'])->name('update');
        Route::delete('/{loyaltyProgram}', [LoyaltyProgramController::class, 'destroy'])->name('destroy');
    });

    // Stock Routes
    Route::get('/stock', StockComponent::class)->name('stock.index');
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/low-stock', [StockController::class, 'lowStock'])->name('low');
        Route::get('/history/{product}', [StockController::class, 'stockHistory'])->name('history');
        Route::post('/bulk-adjustment', [StockController::class, 'bulkAdjustment'])->name('bulk-adjustment');
        Route::get('/export', [StockController::class, 'export'])->name('export');
        Route::get('/create', [StockController::class, 'create'])->name('create');
        Route::post('/', [StockController::class, 'store'])->name('store');
        Route::get('/{stock}/edit', [StockController::class, 'edit'])->name('edit');
        Route::put('/{stock}', [StockController::class, 'update'])->name('update');
        Route::delete('/{stock}', [StockController::class, 'destroy'])->name('destroy');
    });
});

// Error Pages
Route::fallback(function () {
    return view('errors.404');
});
