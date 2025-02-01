<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return $this->successResponse('All Purchase Orders', ['purchase_orders' => PurchaseOrder::paginate(8)]);
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        return $this->successResponse('Purchase Orders', ['purchase_order' => $purchaseOrder]);
    }

    public function store(CreatePurchaseOrderRequest $request)
    {
        $data = array_merge($request->validated(), ['po_number' => mt_rand(0, 99999)]);
        $purchase_order = PurchaseOrder::create($data);

        if (! $purchase_order) {
            return $this->errorResponse('Could not create Purchase Order');
        }

        return $this->successResponse('Purchase Order created successfully', ['purchase_order' => $purchase_order]);
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        $data = $request->validated();

        $data = array_filter($data, function ($value) {
            return !is_null($value) && $value !== '';
        });

        $purchaseOrder->update($data);

        return $this->successResponse('Purchase Order updated successfully', ['purchase_order' => $purchaseOrder]);
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        return $purchaseOrder->delete()
            ?  $this->successResponse('Purchase Order deleted successfully')
            : $this->errorResponse('Could not delete Purchase Order');
    }

    public function import() {}
    public function export() {}
}
