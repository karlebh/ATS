<?php

namespace App\Http\Controllers;

use App\Actions\Task\AssignUserAction;
use App\Actions\Task\IndexAction;
use App\Actions\Task\JobTasksAction;
use App\Actions\Task\StoreAction;
use App\Actions\Task\UnassignUserAction;
use App\Actions\Task\UpdateAction;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        try {
            return (new IndexAction)->execute();
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function jobTasks(int $job_id)
    {
        try {
            return (new JobTasksAction())->execute($job_id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function update(UpdateTaskRequest $request, int $id)
    {
        try {
            return (new UpdateAction())->execute($request->validated(), $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function show(int $id)
    {
        try {
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

            return $this->successResponse('Task retrieved successfully', ['task' => $task]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function store(CreateTaskRequest $request)
    {
        try {
            return (new StoreAction())->execute($request->validated());
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function assignUser(Request $request, int $id)
    {
        $requestData =  $request->validate(['assignee_id' => ['required', 'exists:users,id'],]);
        try {
            return (new AssignUserAction())->execute($requestData, $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function unassignUser(Request $request, int $id)
    {
        $requestData =  $request->validate(['assignee_id' => ['required', 'exists:users,id'],]);
        try {
            return (new UnassignUserAction())->execute($requestData, $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function destroy(int $id)
    {
        try {
            $task = Task::find($id);

            if (! $task) {
                return $this->notFoundResponse("Task with id: {$id} not found");
            }

            return $task->delete()
                ?  $this->successResponse('Task deleted successfully')
                : $this->badRequestResponse('Could not delete task');
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }
}
