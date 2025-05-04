<?php

namespace App\Actions\InspectionTraveler;

use App\Constants\TravelerStatus;
use App\Models\InspectionTraveler;
use App\Traits\ResponseTrait;
use Masterminds\HTML5\Serializer\Traverser;

class UpdateStatusAction
{
    use ResponseTrait;

    public function execute(array $requestData, int $id)
    {
        $inspectionTraveler = InspectionTraveler::find($id);

        if (! $inspectionTraveler) {
            return $this->notFoundResponse("Could not find inspection traveler with id $id");
        }

        $inspectionTraveler->status = "{$requestData['status']}";
        $inspectionTraveler->completed_at = $requestData['status'] === TravelerStatus::COMPLETED ? now() : null;
        $inspectionTraveler->save();

        $inspectionTraveler = $inspectionTraveler->load(['user', 'items', 'operations'])->refresh();

        return $this->successResponse("Inspection Traveler updated successfully", ['inspection_traveler' => $inspectionTraveler]);
    }
}
