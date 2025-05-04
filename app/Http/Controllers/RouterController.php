<?php

namespace App\Http\Controllers;

use App\Actions\Router\DownloadAction;
use App\Actions\Router\SearchAction;
use App\Actions\Router\ShowAction;
use App\Actions\Router\StoreAction;
use App\Actions\Router\UpdateAction;
use App\Constants\JobProgress;
use App\Http\Requests\CreateRouterRequest;
use App\Http\Requests\UpdateRouterRequest;
use App\Http\Resources\AllJobRouterResource;
use App\Http\Resources\CompletedJobRouterResource;
use App\Http\Resources\InProgressJobRouterResource;
use App\Http\Resources\PurchaseOrderResource;
use App\Http\Resources\RouterResource;
use App\Models\PurchaseOrder;
use App\Models\Router;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class RouterController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $allRoutedJobs = PurchaseOrder::with('router')
            ->whereNotNull('router_id')
            ->orderBy('id', 'desc')
            ->paginate(9);
        return AllJobRouterResource::collection($allRoutedJobs);
    }

    public function tabsStats()
    {
        $inProgress = Router::whereHas('purchaseOrder', function ($query) {
            $query->where('status', JobProgress::IN_PROGRESS);
        })
            ->count();

        $completed = Router::whereHas('purchaseOrder', function ($query) {
            $query->where('status', JobProgress::COMPLETED);
        })->count();

        $query = PurchaseOrder::whereNotNull(
            'router_id'
        );

        return $this->successResponse("Tabs counts", [
            'routed_purchase_orders_count' => $query->count(),
            'routed_in_progress_purchase_orders_count' => $query->whereStatus(JobProgress::IN_PROGRESS)->count(),
            'routed_completed_purchase_orders_count' => $query->whereStatus(JobProgress::COMPLETED)->count(),
            'all_routers_count' => Router::count(),
            'all_routers_in_progress_count' => $inProgress,
            'all_routers_in_completed_count' => $completed,
        ]);
    }

    public function inProgressRouters()
    {
        $inProgressRouterJobs = PurchaseOrder::with(['router', 'user'])
            ->whereNotNull('router_id')
            ->whereStatus(JobProgress::IN_PROGRESS)
            ->orderBy('id', 'desc')
            ->paginate(9);

        return InProgressJobRouterResource::collection($inProgressRouterJobs);
    }

    public function completedRouters()
    {
        $completedRouterJobs = PurchaseOrder::with(['router', 'user'])
            ->whereNotNull('router_id')->whereStatus(JobProgress::COMPLETED)->orderBy('id', 'desc')->paginate(9);
        return CompletedJobRouterResource::collection($completedRouterJobs);
    }

    public function show(int $id)
    {
        try {
            return (new ShowAction())->execute($id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function store(CreateRouterRequest $request)
    {
        try {
            return (new StoreAction())->execute($request->validated());
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function update(UpdateRouterRequest $request, int $id)
    {
        try {
            return (new UpdateAction())->execute($request->validated(), $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function search(Request $request)
    {
        $requestData = $request->validate(['q' => ['required', 'min:3', 'string']]);

        try {
            return (new SearchAction())->execute($requestData);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function download(Request $request)
    {
        try {
            return (new DownloadAction())->execute(json_decode($request->ids));
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function destroy(int $id)
    {
        $router = Router::find($id);

        if (! $router) {
            return $this->notFoundResponse("Router with id: {$id} not found");
        }

        return $router->delete()
            ?  $this->successResponse('Router deleted successfully')
            : $this->badRequestResponse('Could not delete Router');
    }
}
