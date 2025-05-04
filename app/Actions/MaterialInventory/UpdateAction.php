<?php

namespace App\Actions\MaterialInventory;

use App\Models\MaterialInventory;
use App\Traits\ResponseTrait;

class UpdateAction
{
    use ResponseTrait;

    public function execute(array $requestData, int $id)
    {
        $material = MaterialInventory::with('vendors')->find($id);

        if (! $material) {
            return $this->badRequestResponse('Could not create material inventory');
        }

        if (! $requestData) {
            return $this->successResponse('No data passed', ['material_inventory' => $material]);
        }

        $material->update($requestData);

        return $this->successResponse('Material inventory updated successfully', [
            'material_inventory' => $material->refresh(),
        ]);
    }
}
