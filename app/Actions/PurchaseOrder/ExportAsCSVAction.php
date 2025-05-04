<?php

namespace App\Actions\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Response;
use ZipArchive;

class ExportAsCSVAction
{
    use ResponseTrait;

    public function execute()
    {
        $purchaseOrders = PurchaseOrder::with('parts')->get();

        if ($purchaseOrders->isEmpty()) {
            return $this->notFoundResponse('No purchase orders available');
        }

        $POCsvFileName = "purchase_orders-" . time() . ".csv";
        $POCsvPath = storage_path('app/public/' . $POCsvFileName);

        $PartsCsvFileName = "parts-" . time() . ".csv";
        $PartsCsvPath = storage_path('app/public/' . $PartsCsvFileName);

        $POCsvFile = fopen($POCsvPath, 'w');
        if (!$POCsvFile) {
            return $this->errorResponse("Unable to create CSV file for purchase orders.");
        }

        $PartsCsvFile = fopen($PartsCsvPath, 'w');
        if (!$PartsCsvFile) {
            fclose($POCsvFile);
            return $this->errorResponse("Unable to create CSV file for parts.");
        }

        $poHeaders = [
            'id',
            'router_id',
            'user_id',
            'po_number',
            'client_name',
            'client_email',
            'client_company_name',
            'start_date',
            'end_date',
            'budget',
            'progress',
            'status',
            'current_team',
            'files',
            'archived',
            'created_at',
            'updated_at',
        ];

        fputcsv($POCsvFile, $poHeaders);

        $partsHeaders = [
            'purchase_order_id',
            'id',
            'uuid',
            'number',
            'name',
            'quantity',
            'price',
            'finish',
            'rev',
            'created_at',
            'updated_at',
        ];

        fputcsv($PartsCsvFile, $partsHeaders);

        foreach ($purchaseOrders as $purchaseOrder) {
            fputcsv($POCsvFile, [
                $purchaseOrder->id,
                $purchaseOrder->router_id,
                $purchaseOrder->user_id,
                $purchaseOrder->po_number,
                $purchaseOrder->client_name,
                $purchaseOrder->client_email,
                $purchaseOrder->client_company_name,
                $purchaseOrder->start_date,
                $purchaseOrder->end_date,
                $purchaseOrder->budget,
                $purchaseOrder->progress,
                $purchaseOrder->status,
                $purchaseOrder->current_team,
                json_encode($purchaseOrder->files),
                $purchaseOrder->archived ? 1 : 0,
                $purchaseOrder->created_at,
                $purchaseOrder->updated_at,
            ]);

            if ($purchaseOrder->parts->isNotEmpty()) {
                foreach ($purchaseOrder->parts as $part) {
                    fputcsv($PartsCsvFile, [
                        $part->purchase_order_id,
                        $part->id,
                        $part->uuid,
                        $part->number,
                        $part->name,
                        $part->quantity,
                        $part->price,
                        $part->finish,
                        $part->rev,
                        $part->created_at,
                        $part->updated_at,
                    ]);
                }
            }
        }

        fclose($POCsvFile);
        fclose($PartsCsvFile);

        $zipFileName = "export-" . time() . ".zip";
        $zipPath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($POCsvPath, $POCsvFileName);
            $zip->addFile($PartsCsvPath, $PartsCsvFileName);
            $zip->close();

            unlink($POCsvPath);
            unlink($PartsCsvPath);

            return Response::download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        } else {
            return $this->errorResponse("Unable to create ZIP file.");
        }
    }
}
