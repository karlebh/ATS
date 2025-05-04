<?php

namespace App\Actions\Client;

use App\Models\Client;
use App\Traits\ResponseTrait;

class StoreClientAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $client = Client::create($requestData + ['user_id' => auth()->id()]);

        if (! $client) {
            return $this->errorResponse('Could not create client');
        }

        return $this->successResponse('Client created', ['client' => $client]);
    }
}
