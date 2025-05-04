<?php

namespace App\Actions\Router;

use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use ZipArchive;

class DownloadAction
{
    use ResponseTrait;

    public function execute(?array $job_ids = [])
    {
        $purchaseOrders = empty($job_ids)
            ? PurchaseOrder::all()
            : PurchaseOrder::whereIn('id', $job_ids)->get();

        if (! $purchaseOrders) {
            return $this->notFoundResponse("These Purchase orders do not exists in record");
        }

        $filesPaths = [];

        foreach ($purchaseOrders as $purchaseOrder) {
            $filesPaths = array_merge(
                $filesPaths,
                is_array($purchaseOrder->files) ? $purchaseOrder->files : (array) json_decode($purchaseOrder->files, true)
            );
        }

        if (! $filesPaths) {
            return $this->notFoundResponse("These purchase orders do not have files attached");
        }

        if (count($filesPaths) === 1) {
            $filePath = storage_path("app/public/{$filesPaths[0]}");
            return response()->download($filePath);
        }

        $zipFileName = "jobs-" . rand(10000, 99999) . ".zip";
        $zipPath = storage_path("app/public/{$zipFileName}");

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($filesPaths as $file) {
            $filePath = storage_path("app/public/{$file}");
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($file));
            }
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
