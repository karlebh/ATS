<?php

namespace App\Http\Resources;

use App\Constants\JobProgress;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeTrackerResource extends JsonResource
{
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
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'comments_count' => $this->comments_count,
            'is_assigned' => $this->isAssigned($this->id),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

            'time_tracker' => $this->timeTrackerArray(
                $this->status,
                $this->start_date,
                $this->end_date,
            ),
        ];
    }

    private function timeTrackerArray(
        string $status,
        DateTime $start_date,
        DateTime $end_date,
    ): array {
        $groups = [
            'in_queue' => [[1, 'in_queue']],
            'in_progress' => [[1, 'in_queue'], [2, 'in_progress']],
            'secondary_ops' => [[1, 'in_queue'], [2, 'in_progress'], [3, 'secondary_ops']],
            'completed' => [
                [1, 'in_queue'],
                [2, 'in_progress'],
                [3, 'secondary_ops'],
                [4, 'completed']
            ],
        ];

        $result = [];
        $previous_end_date = clone $start_date;

        if (isset($groups[$status])) {
            foreach ($groups[$status] as $index => [$group_id, $group_status]) {
                $use_end_date = ($status === 'completed' && $index === array_key_last($groups[$status]));

                $current_start_date = clone $previous_end_date; // Ensure sequential start times
                $current_end_date = $use_end_date ? $end_date : now();

                // Ensure the end_date is always after start_date
                if ($current_end_date <= $current_start_date) {
                    $current_end_date = (clone $current_start_date)->modify('+1 minute');
                }

                $result[] = [
                    'group_id' => $group_id,
                    'title' => $group_status,
                    'start_date' => $current_start_date,
                    'end_date' => $current_end_date,
                ];

                $previous_end_date = $current_end_date; // Set end_date as next stage's start_date
            }
        }

        return $result;
    }

    public function with(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'purchase orders fetched successfully',
        ];
    }

    private function calculateTimeline(): ?string
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }

        $days = floor(Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)));

        return "{$days} days";
    }

    private function isAssigned(int $id): bool
    {
        return PurchaseOrder::where('id', $id)->whereHas('assignedMembers')->exists();
    }
}
