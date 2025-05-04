<?php

namespace App\Actions\Router;

use App\Http\Resources\RouterResource;
use App\Models\PurchaseOrder;
use App\Models\Router;
use App\Traits\ResponseTrait;

class ShowAction
{
    use ResponseTrait;

    public function execute(int $id)
    {
        $router = Router::with(['materialLists', 'purchaseOrder'])
            ->find($id);

        if (! $router) {
            return $this->notFoundResponse("Router of id : {$id} not found");
        }

        return new RouterResource($router);
    }
}
