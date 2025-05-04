<?php

namespace App\Actions\PurchaseOrder;

use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;

class SearchAction
{
    use ResponseTrait;
    public function execute(array $requestData)
    {

        $result = PurchaseOrder::withCount('comments')->with([
            'parts',
            'comments.user',
            'comments.replies'
        ])
            ->where(function ($query) use ($requestData) {
                $query->where('client_name', 'LIKE', "%{$requestData['q']}%")
                    ->orWhere('client_company_name', 'LIKE', "%{$requestData['q']}%")
                    ->orWhere('status', 'LIKE', "%{$requestData['q']}%")
                    ->orWhere('current_team', 'LIKE', "%{$requestData['q']}%");
            })->get();

        if (! $result) {
            return $this->successResponse("No result for query: {$requestData['q']}");
        }

        return $this->successResponse("Results for query: {$requestData['q']}", [
            'data' => PurchaseOrderResource::collection($result)
        ]);
    }
}
