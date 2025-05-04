<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ExportMultipleCSVAction
{
    use ResponseTrait;

    public function execute(?array $ids = null)
    {
        $clients = ! empty($ids) ? Client::find($ids) : Client::all();

        if ($clients->isEmpty()) {
            return $this->notFoundResponse('No clients available');
        }

        $csvFileName = "clients-" . time() . ".csv";
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
            'country',
            'city',
            'created_at',
            'updated_at',
        ];

        fputcsv($csvFile, $headers);

        foreach ($clients as $client) {
            $row = [
                $client->id,
                $client->user_id,
                $client->code,
                $client->name,
                $client->phone,
                $client->email,
                $client->country,
                $client->city,
                $client->created_at,
                $client->updated_at,
            ];
            fputcsv($csvFile, $row);
        }

        fclose($csvFile);

        return Response::download($csvPath, $csvFileName)->deleteFileAfterSend(true);
    }
}
