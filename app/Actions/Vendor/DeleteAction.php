<?php

namespace App\Actions\Vendor;

use App\Models\Vendor;
use App\Traits\ResponseTrait;

class DeleteAction
{
    use ResponseTrait;

    public function execute(int $id)
    {
        $vendor = Vendor::find($id);

        if (! $vendor) {
            return $this->notFoundResponse("Vendor with id: {$id} not found");
        }

        return $vendor->delete()
            ? $this->successResponse('Vendor deleted successfully')
            : $this->errorResponse('Could not delete vendor');
    }
}
