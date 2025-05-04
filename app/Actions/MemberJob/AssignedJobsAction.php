<?php

namespace App\Actions\MemberJob;

use App\Http\Resources\AssignedPurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;

class AssignedJobsAction
{
    use ResponseTrait;
    public function execute()
    {
        $purchaseOrders = PurchaseOrder::with('assignedMembers')->Has('assignedMembers')->latest()->paginate(9);
        return AssignedPurchaseOrderResource::collection($purchaseOrders);
    }
}
