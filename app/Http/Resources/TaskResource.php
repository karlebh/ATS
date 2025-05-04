<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'details' => $this->details,
            'status' => $this->status,
            'user' => $this->whenLoaded('user'),
            'comments' => $this->whenLoaded('comments'),
            'purchase_order' => new PurchaseOrderResource($this->whenLoaded('purchaseOrder')),
            'assigned_members' => $this->whenLoaded('assignedMembers'),
        ];
    }
}
