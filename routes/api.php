<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ModuleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API Routes
Route::get('/modules', [ModuleController::class, 'index']);
Route::get('/modules/{id}', [ModuleController::class, 'show']);
