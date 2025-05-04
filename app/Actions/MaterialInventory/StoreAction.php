<?php

namespace App\Actions\MaterialInventory;

use App\Models\MaterialInventory;
use App\Traits\ResponseTrait;

class StoreAction
{
    use ResponseTrait;
    public function execute(array $requestData)
    {
        $material = MaterialInventory::create($requestData);

        if (! $material) {
            return $this->badRequestResponse('Could not create material inventory');
        }

        return $this->successResponse('Material inventory created successfully', ['material_inventory' => $material]);
    }
}
