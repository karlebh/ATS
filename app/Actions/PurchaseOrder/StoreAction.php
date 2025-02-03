<?php

namespace App\Actions\PurchaseOrder;

use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;

class StoreAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $data = array_merge($requestData, ['po_number' => mt_rand(0, 99999)]);
        $purchase_order = PurchaseOrder::create($data);

        if (! $purchase_order) {
            return $this->errorResponse('Could not create Purchase Order');
        }

        $this->upload($requestData, $purchase_order);

        return $this->successResponse('Purchase Order created successfully', ['purchase_order' => $purchase_order]);
    }

    private function upload($request, $purchase_order)
    {
        if ($request->has('files')) {
            $files = $request->validate([
                'files.*' => 'nullable|mimes:jpg,jpeg,png,gif,pdf,csv',
            ]);

            $store = [];

            try {
                foreach ($files['files'] as $file) {
                    $fileName = time() . '.' . $file->extension();
                    $file->storeAs('uploads', $fileName, 'public');
                    array_push($store, $fileName);
                }

                $store = json_encode($store);

                $purchase_order->update(['files' => $store]);

                return true;
            } catch (\Throwable $th) {
                $this->badRequestResponse('File upload failed', exception: $th);
                return false;
            }

            return false;
        }
    }
}
