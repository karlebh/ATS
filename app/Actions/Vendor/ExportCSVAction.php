<?php

namespace App\Actions\Vendor;

use App\Models\Vendor;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ExportCSVAction
{
    use ResponseTrait;

    public function execute()
    {
        $vendors = Vendor::with('items')->get();

        if ($vendors->isEmpty()) {
            return $this->notFoundResponse('No vendors available');
        }

        $csvFileName = "vendors_and_items-" . time() . ".csv";
        $csvPath = storage_path('app/public/' . $csvFileName);

        $csvFile = fopen($csvPath, 'w');
        if (!$csvFile) {
            return $this->errorResponse("Unable to create CSV file.");
        }

        $headers = [
            'id',
            'user_id',
            'code',
            'name',
            'phone',
            'email',
            'created_at',
            'updated_at',
        ];

        fputcsv($csvFile, $headers);

        foreach ($vendors as $vendor) {
            $vendorData = [
                $vendor->id,
                $vendor->user_id,
                $vendor->code,
                $vendor->name,
                $vendor->phone,
                $vendor->email,
                $vendor->created_at,
                $vendor->updated_at,
            ];

            fputcsv($csvFile, $vendorData);

            if ($vendor->items->isNotEmpty()) {
                $itemsHeaders = [
                    'item_id',
                    'item_vendor_id',
                    'item_name',
                    'item_amount',
                    'item_created_at',
                    'item_updated_at',
                ];

                fputcsv($csvFile, $itemsHeaders);

                foreach ($vendor->items as $item) {
                    $itemData = [
                        $item->id,
                        $item->vendor_id,
                        $item->name,
                        $item->amount,
                        $item->created_at,
                        $item->updated_at,
                    ];
                    fputcsv($csvFile, $itemData);
                }
            }
        }

        fclose($csvFile);

        return Response::download($csvPath, $csvFileName)->deleteFileAfterSend(true);
    }
}
