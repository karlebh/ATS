<?php

namespace App\Actions\Task;

use App\Constants\TaskProgress;
use App\Models\Task;
use App\Notifications\TaskUpdated;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Notification;

class UpdateAction
{
    use ResponseTrait;

    public function execute(array $requestData, int $id)
    {
        if (empty($requestData)) {
            return $this->badRequestResponse('No request data passed');
        }

        $task = Task::with([
            'user',
            'assignedMembers',
            'purchaseOrder',
            'comments.replies',
            'comments.user'
        ])->find($id);

        if (! $task) {
            return $this->notFoundResponse("Could get task with id: {$id}");
        }

        $task->update($requestData);

        $task = $task->refresh();

        Notification::sendNow($task->assignedMembers, new TaskUpdated($task));

        $purchaseOrder = $task->purchaseOrder;

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
            'completed' => $tasks->where('status', TaskProgress::COMPLETED),
            'in_progress' => $tasks->where('status', TaskProgress::IN_PROGRESS),
            'in_queue' => $tasks->where('status', TaskProgress::IN_QUEUE),
            'secondary_ops' => $tasks->where('status', TaskProgress::SECONDARY_OPS),
        ]);
    }
}
