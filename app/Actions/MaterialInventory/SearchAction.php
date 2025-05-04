<?php

namespace App\Actions\MaterialInventory;

use App\Models\MaterialInventory;
use App\Traits\ResponseTrait;

class SearchAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $results = MaterialInventory::with('vendors')
            ->orWhere('title', 'LIKE', "%{$requestData['q']}%")
            ->orWhere('description', 'LIKE', "%{$requestData['q']}%")
            ->get();

        if (! $results) {
            return $this->successResponse("No result for query: {$requestData['q']}");
        }

        return $this->successResponse("Results for query: {$requestData['q']}", ['results' => $results]);
    }
}
