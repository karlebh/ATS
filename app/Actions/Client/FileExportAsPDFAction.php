<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Traits\ResponseTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class FileExportAsPDFAction
{
    use ResponseTrait;

    public function execute()
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            return $this->notFoundResponse('No clients available');
        }

        return response()->streamDownload(function () use ($clients) {
            echo Pdf::loadHtml(
                Blade::render('pdf.clients-pdf', ['clients' => $clients])
            )->stream();
        }, 'clients-pdf.pdf', ['Content-Type' => 'application/pdf']);
    }
}
