<?php

namespace App\Actions\PurchaseOrder;

use App\Http\Requests\POImportCSVRequest;
use App\Models\Part;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader;

class ImportCSVAction
{
    use ResponseTrait;

    public function execute(POImportCSVRequest $request)
    {
        $file = $request->file('csv');
        $fileName = Str::random(10) . '-uploaded-csv-' . $file->getClientOriginalName();
        $path = $file->storeAs("tmp/", $fileName, 'public');

        if (!Storage::disk('public')->exists("tmp/$fileName")) {
            return $this->errorResponse("CSV upload error, kindly upload again");
        }

        $csv = Reader::createFromPath(storage_path("app/public/tmp/$fileName"), 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        foreach ($records as $record) {
            try {
                Part::create(array_filter($record));
            } catch (\Exception $e) {
                Log::error('Failed to import parts from csv', [
                    'error' => $e->getMessage(),
                    'record' => $record,
                ]);
            }

            try {
                PurchaseOrder::create(array_filter($record));
            } catch (\Exception $e) {
                Log::error('Failed to import PurchaseOrder from csv', [
                    'error' => $e->getMessage(),
                    'record' => $record,
                ]);
            }
        }


        Storage::disk('public')->delete("tmp/$fileName");

        return response()->json(['message' => 'CSV data imported successfully!'], 201);
    }
}
