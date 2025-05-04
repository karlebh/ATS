<?php

namespace App\Actions\PurchaseOrder;

use App\Http\Requests\CreatePurchaseOrderRequest;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\TemporaryFile;
use App\Traits\ResponseTrait;
use App\Traits\UtilityTrait;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class StoreAction
{
    use ResponseTrait, UtilityTrait;

    public function execute(CreatePurchaseOrderRequest $request)
    {
        $query = TemporaryFile::where('user_id', auth()->id());

        if ($query->exists() && ! $request->upload_id) {
            return $this->badRequestResponse('Uplaod needs an upload id to properly attach the files to this purchase order. Please include it .');
        }

        if (
            $query->exists() &&
            $query->where('upload_id', $request->safe()->upload_id)->doesntExist()
        ) {
            return $this->badRequestResponse('Invalid upload id');
        }

        $uploadedFiles = $query->where('upload_id', $request->upload_id)->get();

        if (! $request->validated() && $uploadedFiles) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
        }

        $requestData = $request->validated();

        $purchaseOrderData = Arr::except(
            [
                ...$requestData,
                'user_id' => auth()->id(),
                'po_number' => $this->randomNumber()
            ],
            [
                'parts',
                'upload_id',
            ]
        );

        $purchase_order = PurchaseOrder::create($purchaseOrderData);

        if (! $purchase_order) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
            return $this->badRequestResponse('Could not create Purchase Order');
        }

        if (! empty($requestData['parts'])) {
            $purchase_order->parts()->createMany($requestData['parts']);
        }

        if ($uploadedFiles) {
            $this->processFiles($uploadedFiles, $purchase_order);
        }

        $purchase_order->loadCount('comments')->load([
            'parts',
            'user',
            'comments.user',
            'comments.replies'
        ])->refresh();

        return $this->successResponse('Purchase Order created successfully', [
            'purchase_order' => new PurchaseOrderResource($purchase_order)
        ]);
    }
}
