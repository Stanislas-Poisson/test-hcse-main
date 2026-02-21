<?php

use App\Http\Controllers\OfferController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'show'])->middleware('auth')->name('dashboard');

Route::prefix('offers')->name('offers.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/create', [OfferController::class, 'create'])->name('create');
    Route::post('/', [OfferController::class, 'store'])->name('store');
    Route::get('/{offerId}', [OfferController::class, 'show'])->name('show');
    Route::get('/{offerId}/edit', [OfferController::class, 'edit'])->name('edit');
    Route::patch('/{offerId}', [OfferController::class, 'update'])->name('update');
    Route::delete('/{offerId}', [OfferController::class, 'destroy'])->name('destroy');

    // Products management nested under offers
    Route::prefix('{offerId}/products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{productId}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::patch('/{productId}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{productId}', [ProductController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
