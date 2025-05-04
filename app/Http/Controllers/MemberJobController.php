<?php

namespace App\Http\Controllers;

use App\Actions\MemberJob\AssignedJobsAction;
use App\Actions\MemberJob\SearchAction;
use App\Actions\MemberJob\UpdateJobProgressStatusAction;
use App\Constants\JobProgress;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class MemberJobController extends Controller
{
    use ResponseTrait;

    public function memberJobs()
    {
        $assignedJobs = auth()->user()->assignedJobs()->with('comments')->latest()->get();

        return $this->successResponse('Jobs retrieved successfully.', [
            'all' => $assignedJobs,
            'completed' => $assignedJobs->where('status', JobProgress::COMPLETED),
            'in_progress' => $assignedJobs->where('status', JobProgress::IN_PROGRESS),
            'in_queue' => $assignedJobs->where('status', JobProgress::IN_QUEUE),
            'secondary_ops' => $assignedJobs->where('status', JobProgress::SECONDARY_OPS),
        ]);
    }

    public function searchMemberJobs(Request $request)
    {
        $requestData = $request->validate(['q' => ['required', 'min:3', 'string']]);

        try {
            return (new SearchAction())->execute($requestData);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function updateJobProgressStatus(Request $request, int $id)
    {
        $requestData = $request->validate([
            'status' => ['required', 'in:in_queue,in_progress,secondary_ops,completed'],
        ], [
            'status.required' => 'The status field is required.',
            'status.in' => 'The selected status is invalid. Allowed values are: in_queue, in_progress, secondary_ops, and completed.',
        ]);

        try {
            return (new UpdateJobProgressStatusAction())->execute($requestData, $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function assignedJobs()
    {
        try {
            return (new AssignedJobsAction())->execute();
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }
}
