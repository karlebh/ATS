<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisteredUserController as RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\VendorController;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->prefix('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('purchase-orders', [PurchaseOrderController::class, 'index']);
    Route::get('purchase-orders/{purchase-order}', [PurchaseOrderController::class, 'show']);
    Route::post('purchase-orders/store', [PurchaseOrderController::class, 'store']);
    Route::patch('purchase-orders/{purchase-order}', [PurchaseOrderController::class, 'update']);
    Route::delete('purchase-orders/{purchase-order}', [PurchaseOrderController::class, 'destroy']);
    Route::post('purchase-orders/import', [PurchaseOrderController::class, 'import']);
    Route::post('purchase-orders/export', [PurchaseOrderController::class, 'export']);

    Route::resource('client', ClientController::class);
    Route::post('clients/export', [ClientController::class, 'export']);

    Route::resource('vendor', VendorController::class);
});


Route::middleware(['guest'])->prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/login', [LoginController::class, 'store'])->name('login');
});

// Route::middleware(['guest'])->group(function () {
//     Route::post('/forgot-password', [ResetPasswordController::class, 'sendLink'])->name('password.email');
//     Route::post('/add-otp', [ResetPasswordController::class, 'addOTP'])->name('add-otp');
//     Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
// });


require __DIR__ . '/auth.php';
