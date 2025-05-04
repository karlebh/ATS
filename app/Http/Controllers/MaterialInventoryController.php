<?php

namespace App\Http\Controllers;

use App\Actions\MaterialInventory\AlertAdminAction;
use App\Actions\MaterialInventory\SearchAction;
use App\Actions\MaterialInventory\ShowAction;
use App\Actions\MaterialInventory\StoreAction;
use App\Actions\MaterialInventory\UpdateAction;
use App\Http\Requests\CreateMaterialInventoryRequest;
use App\Http\Requests\UpdateMaterialInventoryRequest;
use App\Http\Resources\MaterialInventoryResource;
use App\Models\MaterialInventory;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class MaterialInventoryController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $inventories = MaterialInventory::latest()->get();
        return MaterialInventoryResource::collection($inventories);
    }

    public function show(int $id)
    {
        try {
            return (new ShowAction())->execute($id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function store(CreateMaterialInventoryRequest $request)
    {
        try {
            return (new StoreAction())->execute($request->validated());
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function update(UpdateMaterialInventoryRequest $request, int $id)
    {
        try {
            return (new UpdateAction())->execute($request->validated(), $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function materialInventoriesStats()
    {
        $availables = MaterialInventory::where('quantity', '>', 0)
            ->count();

        $unavailables = MaterialInventory::where('quantity', 0)
            ->count();

        $ordered = MaterialInventory::where('ordered_at')->count();

        return $this->successResponse(
            'Material Inventory dashboard statistics',
            [
                'available' => $availables,
                'unavailable' => $unavailables,
                'ordered' => $ordered,
            ]
        );
    }

    public function availableMaterialInventories()
    {
        $availables = MaterialInventory::where('quantity', '>', 0)
            ->latest()
            ->get();

        return $this->successResponse('Available material inventories', ['materials_inventories' => $availables]);
    }

    public function unavailableMaterialInventories()
    {
        $unavailables = MaterialInventory::where('quantity', 0)
            ->get();

        return $this->successResponse('Unavailable material inventories', ['materials_inventories' => $unavailables]);
    }

    public function orderedMaterialInventories()
    {
        $ordered = MaterialInventory::where('ordered_at')->latest()->get();
        // there should be more to this
        return $this->successResponse('Available material inventories', ['materials_inventories' => $ordered]);
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

    public function destroy(int $id)
    {
        try {
            $material = MaterialInventory::find($id);

            if (! $material) {
                return $this->notFoundResponse("Material with id: {$id} not found");
            }

            return  $material->delete()
                ? $this->successResponse('material inventory deleted succesfully')
                : $this->badRequestResponse('Could not delete material inventory');
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function alertAdmin(Request $request)
    {
        $requestData = $request->validate([
            'title' => ['required', 'min:3', 'string'],
            'message' =>  ['required', 'min:5', 'string']
        ]);

        try {
            return (new AlertAdminAction())->execute($requestData);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        } catch (\Illuminate\Http\Exceptions\ThrottleRequestsException $exception) {
            return $this->badRequestResponse('To many attempts. You can only send one message per minute');
        }
    }
}
