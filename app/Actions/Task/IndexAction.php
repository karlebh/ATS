<?php

namespace App\Actions\Task;

use App\Constants\TaskProgress;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\ResponseTrait;

class IndexAction
{
    use ResponseTrait;

    public function execute()
    {

        $tasks = Task::with([
            'comments.replies',
            'comments.replies.user',
            'comments.user',
            'user',
            'purchaseOrder',
            'assignedMembers'
        ])
            ->latest()
            ->get();

        $tasksByStatus = $tasks->groupBy('status');

        return $this->successResponse("All tasks", [
            'all_tasks' => TaskResource::collection($tasks),
            'in_progress_tasks' => TaskResource::collection($tasksByStatus->get(TaskProgress::IN_PROGRESS, collect())),
            'in_queue_tasks' => TaskResource::collection($tasksByStatus->get(TaskProgress::IN_QUEUE, collect())),
            'secondary_ops_tasks' => TaskResource::collection($tasksByStatus->get(TaskProgress::SECONDARY_OPS, collect())),
            'completed_tasks' => TaskResource::collection($tasksByStatus->get(TaskProgress::COMPLETED, collect())),
        ]);
    }
}
