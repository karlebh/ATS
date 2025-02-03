<?php

namespace App\Actions\PurchaseOrder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class FileExportAction
{
    public function execute()
    {
        $data = DB::table('purchase_orders')->get();

        $csvFileName = 'purchase_orders.csv';
        $csvPath = 'public/' . $csvFileName;
        $csvFile = fopen(storage_path('app/' . $csvPath), 'w');

        if ($data->isNotEmpty()) {
            $headers = array_keys((array) $data[0]);
            fputcsv($csvFile, $headers);

            foreach ($data as $row) {
                fputcsv($csvFile, (array) $row);
            }
        }

        fclose($csvFile);

        return Response::download(storage_path('app/' . $csvPath), $csvFileName)->deleteFileAfterSend(true);
    }
}
