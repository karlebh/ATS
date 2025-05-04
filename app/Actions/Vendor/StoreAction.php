<?php

namespace App\Actions\Vendor;

use App\Models\Vendor;
use App\Traits\ResponseTrait;

class StoreAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $vendor = Vendor::create($requestData);

        if (!$vendor) {
            return $this->errorResponse('Could not create vendor');
        }

        return $this->successResponse('Vendor created', ['vendor' => $vendor]);
    }
}
