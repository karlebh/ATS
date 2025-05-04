<?php

namespace App\Http\Controllers;

use App\Actions\PurchaseOrder\AssignedPurchaseOrdersAction;
use App\Actions\PurchaseOrder\AssignMemberAction;
use App\Actions\PurchaseOrder\AssignMembersAction;
use App\Actions\PurchaseOrder\ExportAsCSVAction;
use App\Actions\PurchaseOrder\ExportMultipleAsCSVAction;
use App\Actions\PurchaseOrder\FileExportAsCSVAction;
use App\Actions\PurchaseOrder\FileExportAsExcelAction;
use App\Actions\PurchaseOrder\FileExportAsPDFAction;
use App\Actions\PurchaseOrder\FileImportAction;
use App\Actions\PurchaseOrder\FilterAction;
use App\Actions\PurchaseOrder\HasAssignedMembersAction;
use App\Actions\PurchaseOrder\ImportCSVAction;
use App\Actions\PurchaseOrder\SearchAction;
use App\Actions\PurchaseOrder\SignleCSVDownloadAction;
use App\Actions\PurchaseOrder\StoreAction;
use App\Actions\PurchaseOrder\UnassignMembersAction;
use App\Actions\PurchaseOrder\UpdateAction;
use App\Http\Requests\CreatePurchaseOrderRequest;
use App\Http\Requests\FilterPurchaseOrderRequest;
use App\Http\Requests\POImportCSVRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Http\Resources\NewRecentPurchaseOrderResource;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        try {
            $fields = [
                'id',
                'po_number',
                'client_name',
                'client_email',
                'client_company_name',
                'budget',
                'progress',
                'status',
                'current_team',
                'start_date',
                'end_date',
                'archived',
            ];

            $filterBy = in_array($request->field, $fields) ? $request->field : 'id';
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';

            $purchase_orders = PurchaseOrder::query()
                ->with([
                    'parts',
                    'user',
                    'comments.user',
                    'comments.replies'
                ])
                ->withCount('comments')->with(['parts', 'comments.user', 'comments.replies'])
                ->withCount('comments')
                ->orderBy('id', 'desc')
                ->paginate(9);

            return PurchaseOrderResource::collection($purchase_orders);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function assignMembers(int $id)
    {
        try {
            return (new AssignMembersAction())->execute($id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function unassignMembers(int $id)
    {
        try {
            return (new UnassignMembersAction())->execute($id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function assignedPurchaseOrders()
    {
        try {
            return (new AssignedPurchaseOrdersAction())->execute();
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function recentPurchaseOrders()
    {
        $purchase_orders = PurchaseOrder::with([
            'parts',
            'comments.user',
            'comments.replies'
        ])->withCount('comments')->orderBy('id', 'desc')->take(9)->get();

        return PurchaseOrderResource::collection($purchase_orders);
    }

    public function show(int $id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'parts',
            'user',
            'comments.user',
            'comments.replies'
        ])
            ->withCount('comments')
            ->find($id);

        if (! $purchaseOrder) {
            return $this->notFoundResponse("Purchase order with id: {$id} not found");
        }

        return $this->successResponse('Purchase Orders', ['purchase_order' => new PurchaseOrderResource($purchaseOrder->load('parts'))]);
    }

    public function store(CreatePurchaseOrderRequest $request)
    {
        try {
            return (new StoreAction())->execute($request);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function update(UpdatePurchaseOrderRequest $request, int $id)
    {
        try {
            return (new UpdateAction())->execute($request, $id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function destroy(int $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);

        if (! $purchaseOrder) {
            return $this->notFoundResponse("Purchase order with id: {$id} not found");
        }

        return $purchaseOrder->delete()
            ?  $this->successResponse('Purchase Order deleted successfully')
            : $this->badRequestResponse('Could not delete Purchase Order');
    }

    public function importCSV(POImportCSVRequest $request)
    {
        try {
            return (new ImportCSVAction())->execute($request);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function exportAsCSV()
    {
        try {
            return (new ExportAsCSVAction())->execute();
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }

    public function exportMultipleAsCSV(Request $request)
    {
        $ids = $request->ids ?? '';

        try {
            return (new ExportMultipleAsCSVAction())->execute(json_decode($ids));
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

    public function exportAsExcel()
    {
        try {
            return (new FileExportAsExcelAction())->execute();
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

    public function singleCSVExport(int $id)
    {
        try {
            return (new SignleCSVDownloadAction())->execute($id);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse("An error occurred", $exception);
        }
    }
}
