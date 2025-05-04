<?php

namespace App\Actions\PurchaseOrder;

use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\TemporaryFile;
use App\Traits\ResponseTrait;
use App\Traits\UtilityTrait;
use Illuminate\Support\Arr;

class UpdateAction
{
    use ResponseTrait, UtilityTrait;

    public function execute(UpdatePurchaseOrderRequest $request, int $id)
    {

        $query = TemporaryFile::where('user_id', auth()->id());

        if ($query->exists() && ! $request->safe()->upload_id) {
            return $this->badRequestResponse('Uplaod needs an upload id to properly attach the files to this purchase order. Please include it .');
        }

        if (
            $query->exists() &&
            $query->where('upload_id', $request->safe()->upload_id)->doesntExist()
        ) {
            return $this->badRequestResponse('Invalid upload id');
        }

        $uploadedFiles = $query->where('upload_id', $request->safe()->upload_id)->get();

        if (! $request->validated() && $uploadedFiles) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
        }

        $purchaseOrder = PurchaseOrder::find($id);

        if (! $purchaseOrder) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
            return $this->notFoundResponse('Purchase order does not exist');
        }

        if ($uploadedFiles->isNotEmpty()) {
            $this->processFiles($uploadedFiles, $purchaseOrder);
        }

        if (empty($request->all()) && $uploadedFiles->isEmpty()) {
            return $this->successResponse(
                'No data were passed, therefore no changes made',
                ['purchase_order' => $purchaseOrder]
            );
        }

        $requestData = $request->validated();

        $purchaseOrderData = Arr::except(
            [...$requestData],
            [
                'parts',
                'upload_id',
            ]
        );

        $data = array_filter($purchaseOrderData, function ($value) {
            return !is_null($value) && $value !== '';
        });

        $purchaseOrder->update($data);

        if ($request['parts']) {
            foreach ($requestData['parts'] as $part) {
                $purchaseOrder->parts()->updateOrCreate(
                    ['id' => $part['id'] ?? ''],
                    $part
                );
            }
        }

        $purchaseOrder =
            $purchaseOrder->loadCount('comments')->load([
                'parts',
                'user',
                'comments.user',
                'comments.replies'
            ])->refresh();

        return $this->successResponse(
            'Purchase Order updated successfully',
            ['purchase_order' => new PurchaseOrderResource($purchaseOrder)]
        );
    }
}
