<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentDetailsController;


Route::prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
    
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware(['auth:sanctum'])->group(function(){
        Route::apiResource('customers', CustomerController::class);
        Route::apiResource('documents', DocumentController::class);
        Route::apiResource('document-details', DocumentDetailsController::class);
        Route::get('documents/status/{status}', [DocumentController::class, 'getByStatus']);
        Route::put('documents/change/status/{id}', [DocumentController::class, 'updateStatus']);
        Route::get('logout', [AuthController::class, 'logout']);
    });
});
