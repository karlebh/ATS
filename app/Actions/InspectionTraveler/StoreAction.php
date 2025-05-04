<?php

namespace App\Actions\InspectionTraveler;

use App\Http\Requests\CreateTravelerRequest;
use App\Models\InspectionTraveler;
use App\Models\TemporaryFile;
use App\Traits\ResponseTrait;
use App\Traits\UtilityTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class StoreAction
{
    use ResponseTrait, UtilityTrait;

    public function execute(CreateTravelerRequest $request)
    {
        $query = TemporaryFile::where('user_id', auth()->id());

        if ($query->exists() && ! $request->upload_id) {
            return $this->badRequestResponse('Uplaod needs an upload id to properly attach the files to this inspection traveler. Please include it .');
        }

        if (
            $query->exists() &&
            $query->where('upload_id', $request->safe()->upload_id)->doesntExist()
        ) {
            return $this->badRequestResponse('Invalid upload id');
        }

        $uploadedFiles = $query->where('upload_id', $request->upload_id)->get();

        if (! $request->validated() && $uploadedFiles) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
        }

        $requestData = $request->validated();

        $travelerData = Arr::except($requestData, ['items', 'operations', 'upload_id']);

        $traveler = InspectionTraveler::create(
            array_merge($travelerData, ['traveler_number' => $this->randomNumber()])
        );

        if (! $traveler) {
            $this->deleteTemporaryUploadedFiles($uploadedFiles);
            return $this->badRequestResponse('Could not create inspection traveler');
        }

        if (! empty($requestData['items'])) {
            $traveler->items()->createMany($requestData['items']);
        }

        if (! empty($requestData['operations'])) {
            $traveler->operations()->createMany($requestData['operations']);
        }

        if (! empty($uploadedFiles)) {
            $this->processFiles($uploadedFiles, $traveler);
        }

        $traveler = $traveler->load(['items', 'operations'])->refresh();

        return $this->successResponse('inspection Traveler created successfully', [
            'inspection_traveler' => $traveler
        ]);
    }
}
