<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

trait ResponseTrait
{

    public function serverErrorResponse($message, \Exception $exception = null): JsonResponse
    {
        if ($exception !== null) {
            Log::error(
                "{$exception->getMessage()} on line {$exception->getLine()} in {$exception->getFile()}"
            );
        }

        $response = [
            'status' => false,
            'code' => 500,
            'message' => $message,
        ];

        return Response::json($response, 500);
    }

    // public function errorResponse($message, \Error $error = null): JsonResponse
    // {
    //     if ($error !== null) {
    //         Log::error(
    //             "{$error->getMessage()} on line {$error->getLine()} in {$error->getFile()}"
    //         );
    //     }
    //     $response = [
    //         'status' => config('go54.status.failed'),
    //         'code' => config('go54.code.server_error'),
    //         'message' => ($this->isProd()) ? "There was an error in your request" : $message,
    //     ];

    //     if (config('app.debug')) {
    //         $response['debug'] = $this->appendDebugData($error);
    //     }

    //     return Response::json($response, ($this->isProd()) ? 406 : 500);
    // }

    public function successResponse(string $message = 'Operation successful', array $data = [], int $code = 200)
    {
        Log::error('Success');
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function errorResponse(string $message = 'An error occurred', int $code = 500, array $data = [], \Exception $exception = null)
    {
        if ($exception != null) {
            Log::error(
                "{$exception->getMessage()} on line {$exception->getLine()} in {$exception->getFile()}"
            );
        }
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function validationErrorResponse($errors, string $message = 'Validation failed')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }

    public function notFoundResponse(string $message = 'Resource not found')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 404);
    }

    public function unauthorizedResponse(string $message = 'Unauthorized')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 401);
    }

    public function forbiddenResponse(string $message = 'Forbidden')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], 403);
    }

    public function noContentResponse()
    {
        return response()->json(null, 204);
    }
}
