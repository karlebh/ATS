<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ExportCSVAction
{
    use ResponseTrait;

    // public function execute()
    // {
    //     $clients = Client::all();

    //     if ($clients->isEmpty()) {
    //         return $this->notFoundResponse('No clients available');
    //     }

    //     $rand = rand(1, 1000);
    //     $csvFileName = "clients-{$rand}.csv";
    //     $csvPath = 'public/' . $csvFileName;
    //     $csvFile = fopen(storage_path('app/' . $csvPath), 'w');

    //     if ($clients->isNotEmpty()) {
    //         $headers = array_keys((array) $clients[0]);
    //         fputcsv($csvFile, $headers);

    //         foreach ($clients as $row) {
    //             fputcsv($csvFile, (array) $row);
    //         }
    //     }

    //     fclose($csvFile);

    //     return Response::download(storage_path('app/' . $csvPath), $csvFileName)->deleteFileAfterSend(true);
    // }

    public function execute()
    {
        $clients = Client::all();

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
