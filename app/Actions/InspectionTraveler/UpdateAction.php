<?php

namespace App\Actions\InspectionTraveler;

use App\Http\Requests\UpdateTravelerRequest;
use App\Models\InspectionTraveler;
use App\Models\TemporaryFile;
use App\Traits\ResponseTrait;
use App\Traits\UtilityTrait;
use Illuminate\Support\Arr;

class UpdateAction
{
    use ResponseTrait, UtilityTrait;

    public function execute(UpdateTravelerRequest $request, int $id)
    {
        $uploadedFiles = TemporaryFile::where('user_id', auth()->id())
            ->get();

        if (! $request->validated() && $uploadedFiles) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
        }

        $requestData = $request->validated();

        $traveler = InspectionTraveler::find($id);

        if (! $traveler) {
            return $this->badRequestResponse('Could not find inspection traveler');
        }

        $travelerData = array_filter(Arr::except($requestData, ['items', 'operations']), function ($value) {
            return !is_null($value) && $value !== '';
        });

        $traveler->update($travelerData);

        if ($requestData['items']) {
            foreach ($requestData['items'] as $items) {
                $traveler->items()->updateOrCreate(
                    ['id' => $items['id'] ?? ''],
                    $items
                );
            }
        }

        if ($requestData['operations']) {
            foreach ($requestData['operations'] as $operations) {
                $traveler->operations()->updateOrCreate(
                    ['id' => $operations['id'] ?? ''],
                    $operations
                );
            }
        }

        if ($uploadedFiles) {
            $this->processFiles($uploadedFiles, $traveler);
        }

        $traveler = $traveler->load(['items', 'operations'])->refresh();

        return $this->successResponse('inspection Traveler updated successfully', [
            'inspection_traveler' => $traveler
        ]);
    }
}
