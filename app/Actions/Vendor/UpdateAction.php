<?php

namespace App\Actions\Vendor;

use App\Models\Vendor;
use App\Traits\ResponseTrait;

class UpdateAction
{
    use ResponseTrait;

    public function execute(array $requestData, int $id)
    {
        $vendor = Vendor::find($id);

        if (! $vendor) {
            return $this->notFoundResponse("Vendor with id: {$id} not found");
        }

        $data = array_filter($requestData, function ($value) {
            return !is_null($value) && $value !== '';
        });


        if (! $data) {
            return $this->successResponse('No data passed', ['vendor' => $vendor]);
        }

        $vendor->update($data);

        return $this->successResponse('Vendor data updated successfully', ['vendor' => $vendor]);
    }
}
