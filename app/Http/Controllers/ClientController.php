<?php

namespace App\Http\Controllers;

use App\Actions\Client\ExportCSVAction;
use App\Actions\Client\ExportMultipleCSVAction;
use App\Actions\Client\FileExportAsCSVAction;
use App\Actions\Client\FileExportAsPDFAction;
use App\Actions\Client\SearchAction;
use App\Actions\Client\StoreClientAction;
use App\Actions\Client\UpdateAction;
use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $clients = Client::paginate(9);
        $clients = Client::latest()->get();
        return ClientResource::collection($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientStoreRequest $request)
    {
        try {
            return (new StoreClientAction())->execute($request->validated());
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $client = Client::find($id);

        if (! $client) {
            return $this->notFoundResponse("Client with id: {$id} not found");
        }

        return $this->successResponse('client retrieved succesfully', ['client' => $client]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $request, int $id)
    {
        try {
            return (new UpdateAction())->execute($request->validated(), $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $client = Client::find($id);

        if (! $client) {
            return $this->notFoundResponse("Client with id: {$id} not found");
        }

        return  $client->delete()
            ? $this->successResponse('client deleted succesfully')
            : $this->badRequestResponse('Could not delete client');
    }

    public function exportAsCSV()
    {
        try {
            return (new ExportCSVAction())->execute();
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function exportMultipleAsCSV(Request $request)
    {
        $ids = $request->ids;

        try {
            return (new ExportMultipleCSVAction())->execute(json_decode($ids));
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function exportAsPDF()
    {
        try {
            return (new FileExportAsPDFAction())->execute();
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
}
