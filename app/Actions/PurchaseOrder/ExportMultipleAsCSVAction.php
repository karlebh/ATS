<?php

namespace App\Actions\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Response;

class ExportMultipleAsCSVAction
{
    use ResponseTrait;

    public function execute(?array $ids = null)
    {
        $purchaseOrders = ! empty($ids) ? PurchaseOrder::with('parts')->whereIn('id', $ids)->get() : PurchaseOrder::with('parts')->get();

        if ($purchaseOrders->isEmpty()) {
            return $this->notFoundResponse('No purchase orders available');
        }

        $csvFileName = "purchase_orders-" . time() . ".csv";
        $csvPath = storage_path('app/public/' . $csvFileName);

        $csvFile = fopen($csvPath, 'w');
        if (!$csvFile) {
            return $this->errorResponse("Unable to create CSV file.");
        }

        $headers = [
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

        fputcsv($csvFile, $headers);

        foreach ($purchaseOrders as $purchaseOrder) {
            $purchaseOrderData = [
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
                $purchaseOrder->archived,
                $purchaseOrder->created_at,
                $purchaseOrder->updated_at,
            ];

            fputcsv($csvFile, $purchaseOrderData);

            if ($purchaseOrder->parts->isNotEmpty()) {
                $partsHeaders = [
                    'part_id',
                    'part_uuid',
                    'part_number',
                    'part_name',
                    'part_quantity',
                    'part_price',
                    'part_finish',
                    'part_rev',
                    'part_created_at',
                    'part_updated_at',
                ];

                fputcsv($csvFile, $partsHeaders);

                foreach ($purchaseOrder->parts as $part) {
                    $partData = [
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
                    ];
                    fputcsv($csvFile, $partData);
                }
            }
        }

        fclose($csvFile);

        return Response::download($csvPath, $csvFileName)->deleteFileAfterSend(true);
    }
}
