<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Traits\ResponseTrait;

class UpdateAction
{
    use ResponseTrait;

    public function execute($requestData, int $id)
    {
        $client = Client::find($id);

        if (! $client) {
            return $this->notFoundResponse("Client with id: {$id} not found");
        }

        if (! $requestData) {
            return $this->successResponse('No data passed', ['client' => $client]);
        }

        $data = array_filter($requestData, function ($value) {
            return ! is_null($value) && $value !== '';
        });

        $client->update($data);

        return $this->successResponse('client updated succesfully', ['client' => $client]);
    }
}
