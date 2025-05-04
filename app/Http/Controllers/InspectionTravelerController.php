<?php

namespace App\Http\Controllers;

use App\Actions\InspectionTraveler\ExportCSVAction;
use App\Actions\InspectionTraveler\SearchAction;
use App\Actions\InspectionTraveler\StoreAction;
use App\Actions\InspectionTraveler\UpdateAction;
use App\Actions\InspectionTraveler\UpdateStatusAction;
use App\Constants\TravelerStatus;
use App\Http\Requests\CreateTravelerRequest;
use App\Http\Requests\UpdateTravelerRequest;
use App\Http\Resources\InspectionTravelerResource;
use App\Models\InspectionTraveler;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Masterminds\HTML5\Serializer\Traverser;

class InspectionTravelerController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $inspectionTraveler =  InspectionTraveler::withCount(['items', 'operations'])
            ->with(['items', 'operations'])
            ->orderBy('id', 'desc')
            ->paginate(9);
        return InspectionTravelerResource::collection($inspectionTraveler);
    }

    public function inspectionTravelerStats()
    {
        return $this->successResponse('inspection traveler stats', [
            'completed' => InspectionTraveler::whereStatus(TravelerStatus::COMPLETED)->count(),
            'in_progress' => InspectionTraveler::whereStatus(TravelerStatus::PENDING)->count(),
            'overdue' => InspectionTraveler::whereStatus(TravelerStatus::OVERDUE)->count(),
            'pending' => InspectionTraveler::whereStatus(TravelerStatus::PENDING)->count(),
            'created' => InspectionTraveler::whereStatus(TravelerStatus::CREATED)->count(),
        ]);
    }

    public function show(int $id)
    {
        $inspectionTraveler = InspectionTraveler::withCount(['items', 'operations'])
            ->with(['items', 'operations'])
            ->find($id);


        if (! $inspectionTraveler) {
            return $this->notFoundResponse("Inspection traveler with id: {$id} not found");
        }

        return new InspectionTravelerResource($inspectionTraveler);
    }

    public function store(CreateTravelerRequest $request)
    {
        try {
            return (new StoreAction())->execute($request);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function update(UpdateTravelerRequest $request, int $id)
    {
        try {
            return (new UpdateAction())->execute($request, $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        $requestData = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in([
                    TravelerStatus::COMPLETED,
                    TravelerStatus::CREATED,
                    TravelerStatus::OVERDUE,
                    TravelerStatus::IN_PROGRESS,
                    TravelerStatus::PENDING,
                ])
            ]
        ], [
            'status.in' => 'The selected status is invalid. Allowed values are: ' . implode(', ', [
                TravelerStatus::CREATED,
                TravelerStatus::COMPLETED,
                TravelerStatus::OVERDUE,
                TravelerStatus::IN_PROGRESS,
                TravelerStatus::PENDING,
            ]),
        ]);

        try {
            return (new UpdateStatusAction())->execute($requestData, $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function allStatuses()
    {
        try {
            $statuses =  [
                TravelerStatus::CREATED,
                TravelerStatus::COMPLETED,
                TravelerStatus::OVERDUE,
                TravelerStatus::IN_PROGRESS,
                TravelerStatus::PENDING,
            ];

            return $this->successResponse('All inspection traveler statuses', ['statuses' => $statuses]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }


    public function completed()
    {
        return $this->successResponse('Completed Inspection Travelers', [
            'inspection_travelers' => InspectionTraveler::withCount(['items', 'operations'])
                ->with(['items', 'operations'])
                ->where('status', TravelerStatus::COMPLETED)
                ->orderBy('id', 'desc')
                ->paginate(9),

            'count' => InspectionTraveler::where('status', TravelerStatus::COMPLETED)->count(),
        ]);
    }

    public function inProgress()
    {
        return $this->successResponse('In Progress Inspection Travelers', [
            'inspection_travelers' => InspectionTraveler::withCount(['items', 'operations'])
                ->with(['items', 'operations'])
                ->whereIn('status', [TravelerStatus::CREATED, TravelerStatus::IN_PROGRESS, TravelerStatus::OVERDUE, TravelerStatus::PENDING])
                ->orderBy('id', 'desc')
                ->paginate(9),
            'count' => InspectionTraveler::whereNull('completed_at')->count()
        ]);
    }

    public function pending()
    {
        return $this->successResponse('Pending Inspection Travelers', [
            'inspection_travelers' => InspectionTraveler::withCount(['items', 'operations'])
                ->with(['items', 'operations'])
                ->where('status', TravelerStatus::PENDING)
                ->orderBy('id', 'desc')
                ->paginate(9),
            'count' => InspectionTraveler::whereNull('completed_at')->count()
        ]);
    }

    public function overdue()
    {
        return $this->successResponse('In Progress Inspection Travelers', [
            'inspection_travelers' => InspectionTraveler::withCount(['items', 'operations'])
                ->with(['items', 'operations'])
                ->where('status', TravelerStatus::OVERDUE)
                ->orderBy('id', 'desc')
                ->paginate(9),
            'count' => InspectionTraveler::whereNull('completed_at')->count()
        ]);
    }

    public function search(Request $request)
    {
        $requestData = $request->validate(['q' => ['required', 'string', 'min:3']]);

        try {
            return (new SearchAction())->execute($requestData);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function exportCSV()
    {
        try {
            return (new ExportCSVAction())->execute();
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function destroy(int $id)
    {
        $inspectionTraveler = InspectionTraveler::find($id);

        if (! $inspectionTraveler) {
            return $this->notFoundResponse("Inspection traveler with id: {$id} not found");
        }

        return $inspectionTraveler->delete()
            ?  $this->successResponse('Inspection Traveler deleted successfully')
            : $this->badRequestResponse('Could not delete Purchase Order');
    }
}
