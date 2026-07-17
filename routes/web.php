<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::post('/unlock', [AuthController::class, 'unlock'])->name('unlock');
    Route::get('/dashboard', [InventoryController::class, 'dashboard'])->name('dashboard');

    // Kategori — pengelolaan penuh cuma Admin
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/categories', [InventoryController::class, 'categoriesIndex'])->name('categories.index');
        Route::get('/categories/create', [InventoryController::class, 'categoriesCreate'])->name('categories.create');
        Route::post('/categories', [InventoryController::class, 'categoriesStore'])->name('categories.store');
        Route::get('/categories/{category}/edit', [InventoryController::class, 'categoriesEdit'])->name('categories.edit');
        Route::put('/categories/{category}', [InventoryController::class, 'categoriesUpdate'])->name('categories.update');
        Route::delete('/categories/{category}', [InventoryController::class, 'categoriesDestroy'])->name('categories.destroy');
    });

    // Produk — Admin & Manajer Gudang boleh lihat, cuma Admin yang CRUD
    Route::middleware(['role:Admin|Manajer Gudang'])->group(function () {
        Route::get('/products', [InventoryController::class, 'productsIndex'])->name('products.index');
    });
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/products/create', [InventoryController::class, 'productsCreate'])->name('products.create');
        Route::post('/products', [InventoryController::class, 'productsStore'])->name('products.store');
        Route::get('/products/export', [InventoryController::class, 'productsExportCsv'])->name('products.export');
        Route::post('/products/import', [InventoryController::class, 'productsImport'])->name('products.import');
        Route::get('/products/{product}/edit', [InventoryController::class, 'productsEdit'])->name('products.edit');
        Route::put('/products/{product}', [InventoryController::class, 'productsUpdate'])->name('products.update');
        Route::delete('/products/{product}', [InventoryController::class, 'productsDestroy'])->name('products.destroy');
        Route::post('/products/{product}/attributes', [InventoryController::class, 'attributesStore'])->name('attributes.store');
        Route::delete('/products/{product}/attributes/{attribute}', [InventoryController::class, 'attributesDestroy'])->name('attributes.destroy');
        Route::post('/products/{product}/archive', [InventoryController::class, 'productsArchive'])->name('products.archive');
        Route::post('/products/{product}/restore', [InventoryController::class, 'productsRestore'])->name('products.restore');
    });

    // Supplier — Admin & Manajer Gudang boleh lihat, cuma Admin yang kelola
    Route::middleware(['role:Admin|Manajer Gudang'])->group(function () {
        Route::get('/suppliers', [InventoryController::class, 'suppliersIndex'])->name('suppliers.index');
    });
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/suppliers/create', [InventoryController::class, 'suppliersCreate'])->name('suppliers.create');
        Route::post('/suppliers', [InventoryController::class, 'suppliersStore'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [InventoryController::class, 'suppliersEdit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [InventoryController::class, 'suppliersUpdate'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [InventoryController::class, 'suppliersDestroy'])->name('suppliers.destroy');
    });

    // Transaksi — semua role boleh lihat, cuma Admin & Manajer Gudang yang boleh catat baru
    Route::middleware(['role:Admin|Manajer Gudang|Staff Gudang'])->group(function () {
        Route::get('/transactions', [InventoryController::class, 'transactionsIndex'])->name('transactions.index');
    });
    // Transaksi — Admin & Manajer Gudang yang mencatat transaksi baru
    // (sesuai bagian "Fitur" dokumen: Manajer Gudang mencatat transaksi masuk/keluar,
    // Staff Gudang cuma Konfirmasi — bagian "Alur" yang sempat menyebut Staff Gudang
    // mencatat transaksi ternyata bertentangan dengan bagian Role & Fitur yang lebih rinci)
    Route::middleware(['role:Admin|Manajer Gudang'])->group(function () {
        Route::post('/transactions', [InventoryController::class, 'transactionsStore'])->name('transactions.store');
    });

    // Konfirmasi/tolak transaksi Pending — tugas utama Staff Gudang (Admin juga boleh)
    Route::middleware(['role:Admin|Staff Gudang'])->group(function () {
        Route::post('/transactions/{transaction}/confirm', [InventoryController::class, 'transactionsConfirm'])->name('transactions.confirm');
        Route::post('/transactions/{transaction}/reject', [InventoryController::class, 'transactionsReject'])->name('transactions.reject');
    });

    // Stock Opname — Admin & Manajer Gudang
    Route::middleware(['role:Admin|Manajer Gudang'])->group(function () {
        Route::get('/stock-opname', [InventoryController::class, 'stockOpnameIndex'])->name('stock-opname.index');
        Route::post('/stock-opname', [InventoryController::class, 'stockOpnameStore'])->name('stock-opname.store');
    });

    // Pengguna — cuma Admin
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Laporan — Admin & Manajer Gudang
    Route::middleware(['role:Admin|Manajer Gudang'])->group(function () {
        Route::get('/reports', [InventoryController::class, 'reportsIndex'])->name('reports.index');
        Route::get('/reports/export/csv', [InventoryController::class, 'reportsExportCsv'])->name('reports.export.csv');
        Route::get('/reports/export/print', [InventoryController::class, 'reportsPrint'])->name('reports.export.print');
    });

    // Laporan Aktivitas Pengguna — cuma Admin (sesuai spek)
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/reports/activity', [InventoryController::class, 'reportsActivity'])->name('reports.activity');
    });

    // Profil Saya — semua role, tiap user ngurus akunnya sendiri (nama, email, foto, password)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Pengaturan aplikasi (nama, logo, ambang stok minimum default) — cuma Admin
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
