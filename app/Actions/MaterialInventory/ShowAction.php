<?php

namespace App\Actions\MaterialInventory;

use App\Models\MaterialInventory;
use App\Traits\ResponseTrait;

class ShowAction
{
    use ResponseTrait;

    public function execute(int $id)
    {
        $material = MaterialInventory::with('vendors')->find($id);

        if (! $material) {
            return $this->notFoundResponse("Material with id: {$id} not found");
        }

        return $this->successResponse('material inventory retrieved', ['material_inventory' => $material]);
    }
}
