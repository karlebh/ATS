<?php

namespace App\Actions\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileImportAction
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

        $csvFileContent = file($filePath);
        $data = array_map('str_getcsv', $csvFileContent);
        $header = array_shift($data);

        foreach ($data as $row) {
            $row = array_combine($header, $row);
            PurchaseOrder::create([
                'po_number' => $row['po_number'],
                'client_name' => $row['client_name'],
                'client_email' => $row['client_email'],
                'client_company_name' => $row['client_company_name'],
                'job_number' => $row['job_number'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
                'budget' => $row['budget'],
                'progress' => $row['progress'],
                'status' => $row['status'],
                'current_team' => $row['current_team'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
            ]);
        }

        return $this->successResponse('CSV Upload succesful');
    }
}
