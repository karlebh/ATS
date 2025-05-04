<?php

namespace App\Actions\Router;

use App\Models\PurchaseOrder;
use App\Models\Router;
use App\Traits\ResponseTrait;

class SearchAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $results = Router::with(['materialLists', 'purchaseOrder'])
            ->where('department', 'LIKE', "%{$requestData['q']}%")
            ->orWhere('instruction', 'LIKE', "%{$requestData['q']}%")
            ->get();

        if (! $results) {
            return $this->successResponse("No results for query: {$requestData['q']}");
        }


        return $this->successResponse("Results for query: {$requestData['q']}", ['data' => $results]);
}
