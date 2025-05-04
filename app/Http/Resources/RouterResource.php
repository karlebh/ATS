<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'department' => $this->department,
            'instruction' => $this->instruction,
            'material_lists' => $this->whenLoaded('materialLists'),
            'purchase_order' => new PurchaseOrderResource($this->whenLoaded('purchaseOrder')),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'router jobs fetched successfully',
        ];
    }
}
