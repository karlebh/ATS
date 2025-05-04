<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Traits\ResponseTrait;

class SearchAction
{
    use ResponseTrait;
    public function execute(array $requestData)
    {
        $results = Client::where('name', 'LIKE', "%{$requestData['q']}%")
            ->orWhere('country', 'LIKE', "%{$requestData['q']}%")
            ->orWhere('city', 'LIKE', "%{$requestData['q']}%")
            ->orWhere('email', 'LIKE', "%{$requestData['q']}%")
            ->get();

        if (! $results) {
            return $this->successResponse("No results for query: {$requestData['q']}");
        }


        return $this->successResponse("Results for query: {$requestData['q']}", ['data' => $results]);
    }
}
