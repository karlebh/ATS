<?php

namespace App\Actions\Router;

use App\Models\Router;
use App\Traits\ResponseTrait;
use Illuminate\Support\Arr;

class UpdateAction
{
    use ResponseTrait;

    public function execute(array $requestData, int $id)
    {
        $router = Router::find($id);

        if (! $router) {
            return $this->notFoundResponse("No router with id: {$id}");
        }

        $routerData = $this->removeEmptyData(Arr::except($requestData, ['materials']));

        $router->update($routerData);

        $materialData = $this->removeEmptyData($requestData['materials']);

        if ($materialData) {
            foreach ($materialData as $material) {
                $router->materialLists()->updateOrCreate(
                    ['id' => $material['id'] ?? ''],
                    $material
                );
            }
        }

        $jobRouter = $router->load(['materialLists', 'purchaseOrder'])->refresh();

        return $this->successResponse('Job route updated successfully', [
            'router' => $jobRouter,
        ]);
    }

    private function removeEmptyData(array $inputData): array
    {
        return array_filter($inputData, function ($value) {
            return !is_null($value) && $value !== '';
        });
    }
}
