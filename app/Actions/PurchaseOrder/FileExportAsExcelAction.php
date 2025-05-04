<?php

namespace App\Actions\PurchaseOrder;

use App\Exports\PurchaseOrders;
use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Facades\Excel;

class FileExportAsExcelAction
{
    public function execute()
    {
        return Excel::download(new PurchaseOrders, 'purchase-orders.xlsx');

        //below does not work
        // $fileName = 'purchase_orders.xlsx';
        // $filePath = public_path("app/$fileName");

        // Excel::store(new PurchaseOrders, $fileName);
        // if (!file_exists($filePath)) {
        //     return response()->json(['error' => 'File could not be generated'], 500);
        // }
        // return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
