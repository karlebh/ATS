<?php

namespace App\Actions\Overview;

use App\Constants\JobProgress;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PurchaseOrderVsJobsCompletedAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {

        if (! empty($requestData['year'])) {
            Cache::forget("purchase_order_jobs_completed_stats");
        }

        $currentYear = $requestData['year'] ?? now()->year;

        $monthlyStats = collect(range(1, 12))->map(function ($month) use ($currentYear) {
            return [
                'month' => Carbon::createFromFormat('m', $month)->format('M'),
                'year' => $currentYear,
                'total' => PurchaseOrder::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->count(),
                'completed' => PurchaseOrder::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->where('status', JobProgress::COMPLETED)
                    ->count(),
            ];
        });

        // $monthlyStats = Cache::rememberForever(
        //     "purchase_order_jobs_completed_stats",
        //     function () use ($currentYear) {
        //         return collect(range(1, 12))->map(function ($month) use ($currentYear) {
        //             return [
        //                 'month' => Carbon::createFromFormat('m', $month)->format('M'),
        //                 'year' => $currentYear,
        //                 'total' => PurchaseOrder::whereYear('created_at', $currentYear)
        //                     ->whereMonth('created_at', $month)
        //                     ->count(),
        //                 'completed' => PurchaseOrder::whereYear('created_at', $currentYear)
        //                     ->whereMonth('created_at', $month)
        //                     ->where('status', JobProgress::COMPLETED)
        //                     ->count(),
        //             ];
        //         });
        //     }
        // );

        return $this->successResponse(
            'Purchase orders vs jobs completed monthly stats for a year',
            ['data' => $monthlyStats]
        );
    }
}
