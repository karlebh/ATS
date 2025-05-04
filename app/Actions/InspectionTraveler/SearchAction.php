<?php

namespace App\Actions\InspectionTraveler;

use App\Models\InspectionTraveler;
use App\Traits\ResponseTrait;

class SearchAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $results = InspectionTraveler::query()
            ->where('shop_name', 'LIKE', "%{$requestData['q']}%")
            ->get();

        if (! $results) {
            return $this->successResponse("No result for query: {$requestData['q']}");
        }

        return $this->successResponse(
            "Results for query: {$requestData['q']}",
            ['results' => $results]
        );
    }
}
