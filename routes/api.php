<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisteredUserController as RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/url', function (Request $request) {
    return response()->json(['url' => config('app.url') . "/hello"]);
});

Route::middleware(['auth:sanctum'])->prefix('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
});

Route::middleware(['guest'])->prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/login', [LoginController::class, 'store'])->name('login');
});

Route::middleware(['guest'])->group(function () {
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendOTP'])->name('password.email');
    Route::post('/authenticate-otp', [ResetPasswordController::class, 'authenticateOTP']);
    Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index']);
    Route::get('/purchase-orders/{purchase-order}', [PurchaseOrderController::class, 'show']);
    Route::get('/purchase-orders/export', [PurchaseOrderController::class, 'export']);
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store']);
    Route::patch('/purchase-orders/{purchase-order}', [PurchaseOrderController::class, 'update']);
    Route::post('/purchase-orders/import', [PurchaseOrderController::class, 'import']);
    Route::delete('/purchase-orders/{purchase-order}', [PurchaseOrderController::class, 'destroy']);


    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{client}', [ClientController::class, 'show']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::patch('/clients/{client}', [ClientController::class, 'update']);
    Route::delete('/clients/{client}', [ClientController::class, 'destroy']);
    Route::get('/clients/export', [ClientController::class, 'export']);


    Route::get('/vendors', [VendorController::class, 'index']);
    Route::get('/vendors/{vendor}', [VendorController::class, 'show']);
    Route::get('/vendors', [VendorController::class, 'export']);
    Route::post('/vendors', [VendorController::class, 'store']);
    Route::patch('/vendors/{vendor}', [VendorController::class, 'update']);
    Route::delete('/vendors/{vendor}', [VendorController::class, 'destroy']);
});




require __DIR__ . '/auth.php';
