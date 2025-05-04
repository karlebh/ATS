<?php

namespace App\Actions\PurchaseOrder;

use App\Models\Part;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class oldCSVUpload
{
    use ResponseTrait;
    public function execute(array $requestData)
    {
        $file = $requestData['csv'];
        $fileName = now()->timestamp . '-' . Str::random(20) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('tmp', $fileName, 'public');
        $filePath = storage_path("app/public/tmp/{$fileName}");

        if (!File::exists($filePath)) {
            return $this->badRequestResponse("CSV file not found!");
        }

        $csvFileContent = array_filter(file($filePath), 'trim');
        $data = array_map('str_getcsv', $csvFileContent);
        $header = array_shift($data);

        foreach ($data as $row) {
            if (count($row) !== count($header)) {
                Log::warning("Skipping row due to mismatched column count", [
                    'header_count' => count($header),
                    'row_count' => count($row),
                    'row_data' => $row
                ]);
                continue;
            }

            $row = array_combine($header, $row);

            if (!$row) {
                Log::error("Failed to combine header and row", ['row' => $row]);
                continue;
            }

            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $row['po_number'] ?? null,
                'router_id' => $row['router_id'] ?? null,
                'client_name' => $row['client_name'] ?? null,
                'client_email' => $row['client_email'] ?? null,
                'client_company_name' => $row['client_company_name'] ?? null,
                'job_number' => $row['job_number'] ?? null,
                'start_date' => $row['start_date'] ?? null,
                'end_date' => $row['end_date'] ?? null,
                'budget' => $row['budget'] ?? null,
                'progress' => $row['progress'] ?? null,
                'status' => $row['status'] ?? null,
                'current_team' => $row['current_team'] ?? null,
                'created_at' => $row['created_at'] ?? now(),
                'updated_at' => $row['updated_at'] ?? now(),
            ]);

            if (isset($row['parts']) && !empty($row['parts'])) {
                $parts = json_decode($row['parts'], true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    foreach ($parts as $part) {
                        Part::create([
                            'uuid' => $part['uuid'] ?? Str::uuid(),
                            'purchase_order_id' => $purchaseOrder->id,
                            'number' => $part['number'] ?? null,
                            'name' => $part['name'] ?? null,
                            'quantity' => $part['quantity'] ?? null,
                            'price' => $part['price'] ?? null,
                            'finish' => $part['finish'] ?? null,
                            'rev' => $part['rev'] ?? null,
                            'created_at' => $part['created_at'] ?? now(),
                            'updated_at' => $part['updated_at'] ?? now(),
                        ]);
                    }
                } else {
                    Log::error("Invalid JSON format for parts data", ['parts' => $row['parts']]);
                }
            }
        }

        return $this->successResponse('CSV Upload successful');
    }
}
