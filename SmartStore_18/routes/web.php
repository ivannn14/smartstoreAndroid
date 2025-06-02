<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\StockAlertController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitsController; // Add this line
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/company-profile', [SettingsController::class, 'companyProfile'])->name('company-profile');
        Route::put('/company-profile/update', [SettingsController::class, 'updateCompanyProfile'])->name('company-profile.update');
        Route::get('/site', [SettingsController::class, 'siteSettings'])->name('site');
        Route::put('/site', [SettingsController::class, 'updateSiteSettings'])->name('site.update');
        Route::get('/payment-types', [SettingsController::class, 'paymentTypes'])->name('payment-types');
        Route::post('/payment-types', [SettingsController::class, 'storePaymentType'])->name('payment-types.store');
        Route::delete('/payment-types/{id}', [SettingsController::class, 'deletePaymentType'])->name('payment-types.delete');
        
        // Units Resource Routes
        Route::get('/units', [UnitsController::class, 'index'])->name('units');
        Route::post('/units/store', [UnitsController::class, 'store'])->name('units.store');
        Route::put('/units/{unit}', [UnitsController::class, 'update'])->name('units.update');
        Route::delete('/units/{unit}', [UnitsController::class, 'destroy'])->name('units.destroy');
    });

    // Sales Routes
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
    Route::get('/sales/export', [SaleController::class, 'export'])->name('sales.export');
    Route::get('/sales/receipt', [SalesController::class, 'receipt'])->name('sales.receipt');

    // Product Image Routes
    Route::post('/products/upload-image/{product}', [ProductController::class, 'uploadImage'])->name('products.upload-image');
    Route::delete('/products/delete-image/{product}', [ProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::resource('products', ProductController::class);
});

Route::middleware('api')->group(function () {
    Route::post('/scan-product', [ProductController::class, 'getProductByBarcode']);
    Route::post('/add-update-product', [ProductController::class, 'addOrUpdateProduct']);
});

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
    Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
    Route::get('/performance', [ReportController::class, 'performanceReport'])->name('performance');
}); // Add this closing brace

Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
Route::get('/reports/performance', [ReportController::class, 'performanceReport'])->name('reports.performance');

Route::middleware(['auth'])->group(function () {
    // Add this with your other routes
    Route::get('/pos', [App\Http\Controllers\POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [App\Http\Controllers\POSController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/product/{id}', [App\Http\Controllers\POSController::class, 'getProduct'])->name('pos.getProduct');
    Route::post('/generate-receipt', [App\Http\Controllers\ReceiptController::class, 'generateReceipt']);
    Route::post('/update-stock', [App\Http\Controllers\ProductController::class, 'updateStock']);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
    Route::get('/stock-alerts', [App\Http\Controllers\StockAlertController::class, 'index'])->name('stock.alerts');
    Route::post('/stock/update/{product}', [App\Http\Controllers\StockAlertController::class, 'updateStock'])->name('stock.update');
    Route::post('/stock/restock/{product}', [App\Http\Controllers\StockAlertController::class, 'restock'])->name('stock.restock');
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
