<?php

namespace App\Actions\InspectionTraveler;

use App\Models\InspectionTraveler;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ExportCSVAction
{
    use ResponseTrait;

    public function execute()
    {
        $travelers = DB::table('inspection_travelers')->get();

        if ($travelers->isEmpty()) {
            return $this->notFoundResponse('No travelers available');
        }

        $rand = rand(1, 1000);
        $csvFileName = "inspection_travelers-{$rand}.csv";
        $csvPath = 'public/' . $csvFileName;
        $csvFile = fopen(storage_path('app/' . $csvPath), 'w');

        if ($travelers->isNotEmpty()) {
            $headers = array_keys((array) $travelers[0]);
            fputcsv($csvFile, $headers);

            foreach ($travelers as $row) {
                fputcsv($csvFile, (array) $row);
            }
        }

        fclose($csvFile);

        return Response::download(storage_path('app/' . $csvPath), $csvFileName)->deleteFileAfterSend(true);
    }
}
