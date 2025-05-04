<?php

namespace App\Actions\Router;

use App\Http\Resources\RouterResource;
use App\Models\PurchaseOrder;
use App\Models\Router;
use App\Traits\ResponseTrait;
use Illuminate\Support\Arr;

class StoreAction
{
    use ResponseTrait;
    public function execute(array $requestData)
    {
        $router = Router::create([
            'user_id' => auth()->id(),
            'department' => $requestData['department'],
            'instruction' => $requestData['instruction']
        ]);

        $purchaseOrder = PurchaseOrder::find($requestData['purchase_order_id']);

        if (! $purchaseOrder) {
            return $this->notFoundResponse("Purchase order with id: {$requestData['purchase_order_id']} not found");
        }

        $purchaseOrder->update(['router_id' => $router->id]);

        if (! $router) {
            return $this->badRequestResponse('Could not create job router');
        }

        if (! empty($requestData['materials'])) {
            $router->materialLists()->createMany($requestData['materials']);
        }

        $router = $router->load(['materialLists', 'purchaseOrder'])->refresh();

        return $this->successResponse('Job routed successfully', [
            'router' => new RouterResource($router)
        ]);
    }
}
