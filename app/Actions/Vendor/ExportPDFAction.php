<?php

namespace App\Actions\Vendor;

use App\Models\Vendor;
use App\Traits\ResponseTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class ExportPDFAction
{
    use ResponseTrait;
    public function execute()
    {
        $vendors = Vendor::all();

        if ($vendors->isEmpty()) {
            return $this->notFoundResponse('No vendors available');
        }

        return response()->streamDownload(function () use ($vendors) {
            echo Pdf::loadHtml(
                Blade::render('pdf.vendor-pdf', ['vendors' => $vendors])
            )->stream();
        }, 'vendor-pdf.pdf', ['Content-Type' => 'application/pdf']);
    }
}
