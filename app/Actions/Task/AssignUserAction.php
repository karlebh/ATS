<?php

namespace App\Actions\Task;

use App\Models\User;
use App\Models\Task;
use App\Notifications\TaskAssigned;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Notification;

class AssignUserAction
{
    use ResponseTrait;
    public function execute($requestData, int $id)
    {
        $task = Task::find($id);

        if (! $task) {
            return $this->notFoundResponse("Could not get task with id: {$id}");
        }

        $assignee = User::find($requestData['assignee_id']);

        if (! $assignee) {
            return $this->notFoundResponse("Could not get user with id: {$requestData['assignee_id']}");
        }

        if ($task->assignedMembers()->where('user_id', $assignee->id)->exists()) {
            return $this->badRequestResponse('User already attached to this task');
        }

        $task->assignedMembers()->syncWithoutDetaching([$assignee->id]);

        $task = $task->load([
            'user',
            'assignedMembers',
            'purchaseOrder',
            'comments.replies',
            'comments.replies.user',
            'comments.user'
        ]);

        Notification::sendNow($assignee, new TaskAssigned($task));

        return $this->successResponse('User assigned to task successfully', ['task' => $task]);
    }
}
