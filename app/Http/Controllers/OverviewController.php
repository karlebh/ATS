<?php

namespace App\Http\Controllers;

use App\Actions\Overview\PurchaseOrderVsJobsCompletedAction;
use App\Constants\JobProgress;
use App\Constants\UserRole;
use App\Models\Job;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    use ResponseTrait;

    public function dashboardJobStatsCount()
    {
        return $this->successResponse(
            'dashboard stats',
            [
                'total_purchase_orders' => PurchaseOrder::count(),
                'total_completed_jobs' => PurchaseOrder::whereStatus(JobProgress::COMPLETED)->count(),
                'floor_team_jobs' => PurchaseOrder::where('current_team', UserRole::FLOOR_TEAM)->count(),
                'jobs_under_inspection' => PurchaseOrder::whereNot('status', JobProgress::COMPLETED)->count(),
            ]
        );
    }

    public function getDepartments()
    {
        $departments = DB::table('departments')->get();
        return $this->successResponse('All Departments', ['departments' => $departments]);
    }

    public function completedJobs()
    {
        // Cache::rememberForever(
        //     'jobs_completed_count',
        //     fn() => User::where('role', UserRole::FLOOR_TEAM)->get()
        // );
        $purchase_orders =
            PurchaseOrder::where('status', JobProgress::COMPLETED)->get();

        return $this->successResponse('Total completed jobs', ['purchase_orders' => $purchase_orders]);
    }

    public function getFloorTeamMembers()
    {
        // $users = Cache::rememberForever(
        //     'floor_team_members',
        //     fn() => User::where('role', UserRole::FLOOR_TEAM)->get()
        // );
        return $this->successResponse('All floor team members', ['users' => User::where('role', UserRole::FLOOR_TEAM)->latest()->get()]);
    }

    public function getFloorTeamJobs()
    {
        // $purchaseOrders = Cache::rememberForever(
        //     'floor_team_jobs',
        //     fn() => PurchaseOrder::where('current_team', UserRole::FLOOR_TEAM)->get()
        // );
        return $this->successResponse('All floor team members jobs', [
            'purchase_orders' =>
            PurchaseOrder::where('current_team', UserRole::FLOOR_TEAM)->latest()->get()
        ]);
    }

    public function JobsInProgressCount()
    {
        // $purchaseOrders = Cache::rememberForever(
        //     'jobs_in_progress_count',
        //     fn() => PurchaseOrder::where('status', JobProgress::IN_PROGRESS)->count()
        // );
        return $this->successResponse('All floor team members', [
            'purchase_orders'
            =>
            PurchaseOrder::where('status', JobProgress::IN_PROGRESS)->count()
        ]);
    }

    public function JobsCompletedCount()
    {
        // $purchaseOrders = Cache::rememberForever(
        //     'jobs_completed_count',
        //     fn() => PurchaseOrder::where('status', JobProgress::COMPLETED)->count()
        // );
        return $this->successResponse('All floor team members', [
            'purchase_orders' =>
            PurchaseOrder::where('status', JobProgress::COMPLETED)->count()
        ]);
    }

    public function AllJobsCount()
    {
        // $purchaseOrders = Cache::rememberForever(
        //     'purchase_orders_count',
        //     fn() =>
        //     PurchaseOrder::count()
        // );

        return $this->successResponse('All floor team members', [
            'purchase_orders' =>
            PurchaseOrder::count()
        ]);
    }

    public function PurchaseOrderVsJobsCompleted(Request $request)
    {
        $requestData = $request->validate([
            'year' => ['nullable', 'integer', 'digits:4', 'min:2025', 'max:' . date('Y')]
        ], [
            'year.max' => 'The year cannot be greater than current year.'
        ]);

        try {
            return (new PurchaseOrderVsJobsCompletedAction())->execute($requestData);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }
}
