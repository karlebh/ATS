<?php

namespace App\Actions\Vendor;

use App\Models\Vendor;
use App\Traits\ResponseTrait;

class SearchAction
{
    use ResponseTrait;
    public function execute(array $requestData)
    {
        $result = Vendor::where('name', 'LIKE', "%{$requestData['q']}%")->get();

        if (! $result) {
            return $this->successResponse("No result for query: {$requestData['q']}");
        }

        return $this->successResponse("Results for query: {$requestData['q']}", ['results' => $result]);
    }
}
