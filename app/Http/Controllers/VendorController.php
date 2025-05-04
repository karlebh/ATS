<?php

namespace App\Http\Controllers;

use App\Actions\Vendor\DeleteAction;
use App\Actions\Vendor\ExportCSVAction;
use App\Actions\Vendor\ExportMultipleCSVAction;
use App\Actions\Vendor\ExportPDFAction;
use App\Actions\Vendor\SearchAction;
use App\Actions\Vendor\StoreAction;
use App\Actions\Vendor\UpdateAction;
use App\Http\Requests\VendorStoreRequest;
use App\Http\Requests\VendorUpdateRequest;
use App\Models\Vendor;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $vendors = Vendor::with(['items'])
            ->withCount(['items'])
            ->withSum('items', 'amount')
            ->latest()
            ->get();

        return $this->successResponse('All vendors', ['vendors' => $vendors]);
    }

    public function show(int $id)
    {
        $vendor = Vendor::find($id);

        if (! $vendor) {
            return $this->notFoundResponse("Vendor with id: {$id} not found");
        }

        return $this->successResponse('Vendor', ['vendor' => $vendor]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VendorStoreRequest $request)
    {
        try {
            return (new StoreAction())->execute($request->validated());
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function update(VendorUpdateRequest $request, int $id)
    {
        try {
            return (new UpdateAction())->execute($request->validated(), $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function destroy(int $id)
    {
        try {
            return (new DeleteAction())->execute($id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function search(Request $request)
    {
        $requestData = $request->validate(['q' => ['required', 'min:3', 'string']]);

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

    public function exportMultipleAsCSV(Request $request)
    {
        try {
            return (new ExportMultipleCSVAction())->execute(json_decode($request->ids));
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function exportPDF()
    {
        try {
            return (new ExportPDFAction())->execute();
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }


    public function topClients()
    {
        try {
            $clients = Vendor::orderBy('grand_total', 'desc');

            return $this->successResponse('Top Clients', [
                'clients' => $clients->take(3)->get(),
                'clients_count' => $clients->count()
            ]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }
}
