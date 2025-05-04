<?php

namespace App\Http\Controllers;

use App\Http\Resources\PurchaseOrderResource;
use App\Http\Resources\TimeTrackerResource;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TimeTrackerController extends Controller
{
    use ResponseTrait;
    public function __invoke()
    {
        try {
            $demoTimeTracker = PurchaseOrder::latest()->take(10)->get();
            $liveTimeTracker = PurchaseOrder::has('assignedMembers')->latest()->take(10)->get();

            return $this->successResponse('', [TimeTrackerResource::collection($demoTimeTracker)]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }
}
