<?php

namespace App\Actions\PurchaseOrder;

use App\Http\Resources\AssignedPurchaseOrderResource;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;

class AssignedPurchaseOrdersAction
{
    use ResponseTrait;

    public function execute()
    {
        $purchaseOrders = PurchaseOrder::with([
            'parts',
            'user',
            'comments.user',
            'comments.replies'
        ])
            ->withCount('comments')
            ->has('assignedMembers')
            ->orderBy('id', 'desc')
            ->get();

        return AssignedPurchaseOrderResource::collection($purchaseOrders);
    }
}
