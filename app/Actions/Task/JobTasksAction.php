<?php

namespace App\Actions\Task;

use App\Constants\TaskProgress;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;

class JobTasksAction
{
    use ResponseTrait;

    public function execute(int $jobId)
    {
        $purchaseOrder = PurchaseOrder::find($jobId);

        if (! $purchaseOrder) {
            return $this->notFoundResponse("Purchase order with id: {$jobId} not found");
        }

        $tasks = $purchaseOrder->tasks()->with([
            'comments.replies',
            'comments.replies.user',
            'comments.user',
            'user',
            'purchaseOrder',
            'assignedMembers'
        ])
            ->latest()
            ->get();

        return $this->successResponse('Tasks retrieved successfully.', [
            'all' => $tasks,
            'completed' => $tasks->where('status', TaskProgress::COMPLETED)->values(),
            'in_progress' => $tasks->where('status', TaskProgress::IN_PROGRESS)->values(),
            'in_queue' => $tasks->where('status', TaskProgress::IN_QUEUE)->values(),
            'secondary_ops' => $tasks->where('status', TaskProgress::SECONDARY_OPS)->values(),
        ]);
    }
}
