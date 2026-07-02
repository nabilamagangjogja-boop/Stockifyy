<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('splash');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [InventoryController::class, 'dashboard'])->name('dashboard');

    Route::get('/categories', [InventoryController::class, 'categoriesIndex'])->name('categories.index');
    Route::get('/categories/create', [InventoryController::class, 'categoriesCreate'])->name('categories.create');
    Route::post('/categories', [InventoryController::class, 'categoriesStore'])->name('categories.store');
    Route::get('/categories/{category}/edit', [InventoryController::class, 'categoriesEdit'])->name('categories.edit');
    Route::put('/categories/{category}', [InventoryController::class, 'categoriesUpdate'])->name('categories.update');
    Route::delete('/categories/{category}', [InventoryController::class, 'categoriesDestroy'])->name('categories.destroy');

    Route::get('/products', [InventoryController::class, 'productsIndex'])->name('products.index');
    Route::get('/products/create', [InventoryController::class, 'productsCreate'])->name('products.create');
    Route::post('/products', [InventoryController::class, 'productsStore'])->name('products.store');
    Route::get('/products/{product}/edit', [InventoryController::class, 'productsEdit'])->name('products.edit');
    Route::put('/products/{product}', [InventoryController::class, 'productsUpdate'])->name('products.update');
    Route::delete('/products/{product}', [InventoryController::class, 'productsDestroy'])->name('products.destroy');

    Route::get('/suppliers', [InventoryController::class, 'suppliersIndex'])->name('suppliers.index');
    Route::post('/suppliers', [InventoryController::class, 'suppliersStore'])->name('suppliers.store');

    Route::get('/transactions', [InventoryController::class, 'transactionsIndex'])->name('transactions.index');
    Route::post('/transactions', [InventoryController::class, 'transactionsStore'])->name('transactions.store');

    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });

    Route::get('/reports', function () {
        return view('inventory.reports.index');
    })->name('reports.index');
});
