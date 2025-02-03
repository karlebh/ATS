<?php

namespace App\Http\Controllers;

use App\Actioms\PurchaseOrder\ExportAction;
use App\Actioms\PurchaseOrder\FileExportAction;
use App\Http\Requests\CreatePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return $this->successResponse('All Purchase Orders', ['purchase_orders' => PurchaseOrder::paginate(8)]);
    }

    public function recentPurchaseOrders()
    {
        $purchase_orders = PurchaseOrder::latest()->paginate(8);
        return $this->successResponse('Recent Purchase Orders', ['purchase_orders' => $purchase_orders]);
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        return $this->successResponse('Purchase Orders', ['purchase_order' => $purchaseOrder]);
    }

    public function store(CreatePurchaseOrderRequest $request)
    {
        $data = array_merge($request->validated(), ['po_number' => mt_rand(0, 99999)]);
        $purchase_order = PurchaseOrder::create($data);

        if (! $purchase_order) {
            return $this->badRequestResponse('Could not create Purchase Order');
        }

        return $this->successResponse('Upload succesful', $request->all());

        if ($request->has('file')) {

            return $this->successResponse('Upload succesful', $request->all());
            $files = [];

            foreach ($request->input('file.*')['file'] as $file) {
                $newFilename = Str::after($file, 'tmp/');
                $toUploadFilePath = "uploads/$newFilename";
                Storage::disk('public')->move($file, $toUploadFilePath);
                $files[] = $toUploadFilePath;
            }

            return $this->successResponse('Upload succesful', [$files]);

            $purchase_order->update(['files' => json_encode($files)]);
        } else {
            return '';
        }

        return $this->successResponse('Purchase Order created successfully', ['purchase_order' => $purchase_order->fresh()]);
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        $data = $request->validated();

        $data = array_filter($data, function ($value) {
            return !is_null($value) && $value !== '';
        });

        $purchaseOrder->update($data);

        return $this->successResponse('Purchase Order updated successfully', ['purchase_order' => $purchaseOrder]);
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        return $purchaseOrder->delete()
            ?  $this->successResponse('Purchase Order deleted successfully')
            : $this->badRequestResponse('Could not delete Purchase Order');
    }

    public function import() {}


    public function export()
    {
        try {
            return (new FileExportAction())->execute();
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function revert(Request $request)
    {
        Storage::disk('public')->delete($request->getContent());
    }

    public function upload(Request $request)
    {
        $files = $request->validate([
            'file.*' => 'nullable|mimes:jpg,jpeg,png,gif,pdf,csv',
        ]);

        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }

        $paths = [];

        foreach ($files['file'] as $file) {
            $fileName = now()->timestamp . '-' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $paths[] = $file->storeAs('tmp', $fileName, 'public');
        }

        return $paths;
    }

    private function deleteOldFiles($oldFilesJson)
    {
        try {
            $oldFiles = json_decode($oldFilesJson, true);

            if (!is_array($oldFiles)) {
                $this->badRequestResponse('Invalid JSON format: Expected an array.');
            }

            foreach ($oldFiles as $oldFile) {
                $filePath = storage_path('app/uploads/' . $oldFile);

                if (file_exists($filePath)) {
                    if (!unlink($filePath)) {
                        $this->badRequestResponse("Failed to delete file: {$filePath}");
                    }
                }
            }
        } catch (\Exception $e) {
            $this->badRequestResponse('Error deleting old files', exception: $e);
        }
    }
}
