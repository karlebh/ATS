<?php

namespace App\Actions\Task;

use App\Constants\UserRole;
use App\Mail\NewTaskAssignment;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskCreated;
use App\Traits\ResponseTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class StoreAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $task = Task::create(
            [
                'user_id' => auth()->id(),
                ...Arr::except($requestData, ['assignee_id'])
            ]
        );

        if (! $task) {
            return $this->notFoundResponse("Could not create task");
        }

        $floorTeam = User::where('role', UserRole::FLOOR_TEAM)->get();

        Notification::sendNow($floorTeam, new TaskCreated($task));

        $task = $task->load([
            'user',
            'assignedMembers',
            'purchaseOrder',
            'comments.replies',
            'comments.replies.user',
            'comments.user'
        ])->refresh();

        try {
            Mail::to($floorTeam)->send(new NewTaskAssignment($task, auth()->user()));
        } catch (\Exception $exception) {
            $this->serverErrorResponse('Failed to send task assignment mail', $exception);
        }

        return response()->json([
            'message' => 'Task created successfully.',
            'task' => $task
        ], 201);
    }
}
