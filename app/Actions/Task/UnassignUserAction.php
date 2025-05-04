<?php

namespace App\Actions\Task;

use App\Models\Task;
use App\Models\User;
use App\Traits\ResponseTrait;

class UnassignUserAction
{
    use ResponseTrait;

    public function execute(array $requestData, int $id)
    {
        $task = Task::with([
            'user',
            'assignedMembers',
            'purchaseOrder',
            'comments.replies',
            'comments.replies.user',
            'comments.user'
        ])->find($id);

        if (! $task) {
            return $this->notFoundResponse("Could not get task with id: {$id}");
        }

        $assignee = User::find($requestData['assignee_id']);

        if (! $assignee) {
            return $this->notFoundResponse("Could not get user with id: {$requestData['assignee_id']}");
        }

        if (! $task->assignedMembers()->where('user_id', $assignee->id)->exists()) {
            return $this->badRequestResponse('User was not attached to this task');
        }

        return $this->successResponse('User removed from task successfully', ['task' => $task]);
    }
}
