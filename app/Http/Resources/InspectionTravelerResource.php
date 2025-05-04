<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionTravelerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'shop_name' => $this->shop_name,
            'shop_email' => $this->shop_email,
            'traveler_number' => $this->traveler_number,
            'start_date' => $this->start_date,
            'due_date' => $this->due_date,
            'completed_date' => $this->completed_date,
            'files' => $this->files,
            'status' => $this->status,
            'items_count' => $this->items_count,
            'operations_count' => $this->operations_count,
            'items' => $this->whenLoaded('items'),
            'operations' => $this->whenLoaded('operations'),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'inspection traveler fetched successfully',
        ];
    }
}
