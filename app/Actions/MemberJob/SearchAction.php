<?php

namespace App\Actions\MemberJob;

use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;

class SearchAction
{
    use ResponseTrait;
    public function execute(array $requestData)
    {

        $result = auth()->user()->assignedJobs()
            ->where(function ($query) use ($requestData) {
                $query->where('client_name', 'LIKE', "%{$requestData['q']}%")
                    ->orWhere('client_company_name', 'LIKE', "%{$requestData['q']}%")
                    ->orWhere('status', 'LIKE', "%{$requestData['q']}%")
                    ->orWhere('current_team', 'LIKE', "%{$requestData['q']}%");
            })
            ->get();


        if (! $result) {
            return $this->successResponse("No result for query: {$requestData['q']}");
        }

        return $this->successResponse("Results for query: {$requestData['q']}", ['data' => $result]);
    }
}
