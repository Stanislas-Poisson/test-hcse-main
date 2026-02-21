<?php

use App\Http\Controllers\Api\OfferController as ApiOfferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public endpoint to list published offers
Route::get('/offers', [ApiOfferController::class, 'index']);
