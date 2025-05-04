<?php

namespace App\Http\Resources;

use App\Constants\JobProgress;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InProgressJobRouterResource extends JsonResource
{
    private function calculateTimeline(): ?string
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }

        $days = floor(Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)));

        return "{$days} days";
    }


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'router_id' => $this->router_id,
            'po_number' => $this->po_number,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'client_company_name' => $this->client_company_name,
            'budget' => $this->budget,
            'progress' => JobProgress::getPercentage($this->status),
            'status' => $this->status,
            'current_team' => $this->current_team,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'timeline' => $this->calculateTimeline(),
            'files' => $this->files,
            'attachments' => is_array($this->files) ? count($this->files) : 0,
            'parts' => $this->whenLoaded('parts'),
            'user' => $this->whenLoaded('user'),
            'is_assigned' => $this->isAssigned($this->id),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }

    private function isAssigned(int $id): bool
    {
        return PurchaseOrder::where('id', $id)->whereHas('assignedMembers')->exists();
    }

    public function with(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'In progress router jobs fetched successfully',
        ];
    }
}
