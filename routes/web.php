<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InspectionTravelerController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\VendorController;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welcome to ATS API';
});

Route::get('/try', function () {
    return [1, 2, 3];
});

Route::get('/jobs', function () {
    $purchase_order = PurchaseOrder::with('parts')->paginate(20);
    return PurchaseOrderResource::collection($purchase_order);
});

Route::get('/clients-export-csv', [ClientController::class, 'exportAsCSV']);
Route::get('/clients-export-pdf', [ClientController::class, 'exportAsPDF']);

Route::get('/vendors-export-csv', [VendorController::class, 'exportCSV'])->name('vendor.export-csv');
Route::get('/vendors-export-pdf', [VendorController::class, 'exportPDF'])->name('vendor.export-pdf');

Route::get('/inspection-travelers-export-csv', [InspectionTravelerController::class, 'exportCSV']);
Route::get('/purchase-orders-multiple-export-csv', [PurchaseOrderController::class, 'exportMultipleAsCSV'])
    ->name('purchase-order.export-multiple-csv');
Route::get('/purchase-orders-export-csv', [PurchaseOrderController::class, 'exportAsCSV'])->name('purchase-order.export-csv');
Route::get('/purchase-orders-export-excel', [PurchaseOrderController::class, 'exportAsExcel'])->name('purchase-order.export-excel');
Route::get('/purchase-orders-export-pdf', [PurchaseOrderController::class, 'exportAsPDF'])->name('purchase-order.export-pdf');

Route::get('/purchase-orders-single-csv-export/{id}', [PurchaseOrderController::class, 'singleCSVExport']);

Route::get('/routers-download', [RouterController::class, 'download'])->name('router.multi-download');


Route::get('/clients-export-multiple-csv', [ClientController::class, 'exportMultipleAsCSV']);
Route::get('/vendors-export-multiple-csv', [VendorController::class, 'exportMultipleAsCSV'])->name('vendor.export-csv');

Route::get('/job-journals-export-csv', [PurchaseOrderController::class, 'exportAsCSV']);

Route::get('/files/download', [FileController::class, 'download'])->name('file.download');
