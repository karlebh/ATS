<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
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
        return $this->successResponse('All clients', Client::paginate(8));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientStoreRequest $request)
    {
        $code =  rand(00_000, 99_999);
        $data = $request->validated();

        $client = Client::create(array_merge($data, ['code' => $code]));

        if (! $client) {
            return $this->errorResponse('Could not create client');
        }

        return $this->successResponse('Client created', ['client' => $client]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return $this->successResponse('client retrieved succesfully', ['client' => $client]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $request, Client $client)
    {
        $data = array_filter($request->validated(), function ($value) {
            return !is_null($value) && $value !== '';
        });

        $client->update($data);

        return $this->successResponse('client updated succesfully', ['client' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        return  $client->delete()
            ? $this->successResponse('client deleted succesfully')
            : $this->errorResponse('Could not delete client');
    }

    public function export() {}
}
