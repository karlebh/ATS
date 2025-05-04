<?php

namespace App\Actions\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class FileExportAsPDFAction
{
    use ResponseTrait;
    public function execute()
    {
        $purchase_orders = PurchaseOrder::all();

        if ($purchase_orders->isEmpty()) {
            return $this->notFoundResponse('No purchase orders available');
        }

        return response()->streamDownload(function () use ($purchase_orders) {
            echo Pdf::loadHtml(
                Blade::render('pdf.purchase_orders-pdf', ['purchase_orders' => $purchase_orders])
            )->stream();
        }, 'purchase_orders-pdf.pdf', ['Content-Type' => 'application/pdf']);
    }
}
