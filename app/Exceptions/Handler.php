<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    public function register(): void
    {
        $this->renderable(function (ThrottleRequestsException $e) {
            return $this->badRequestResponse('To many attempts. You can only send one message per minute');
        });
    }
}
