<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\FinancialRecordController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('update', [ProfileController::class, 'update']);
    
    Route::post('transaction', [FinancialRecordController::class, 'store']);
    Route::post('transaction/{id}', [FinancialRecordController::class, 'update']);
    
    Route::get('users/transactions', [UserController::class, 'list']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
