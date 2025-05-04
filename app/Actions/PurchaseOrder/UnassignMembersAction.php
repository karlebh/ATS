<?php

namespace App\Actions\PurchaseOrder;

use App\Constants\JobProgress;
use App\Constants\UserRole;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Notifications\JobUnassigned;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class UnassignMembersAction
{
    use ResponseTrait;

    public function execute(int $id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'parts',
            'user',
            'comments.user',
            'comments.replies'
        ])
            ->withCount('comments')->find($id);

        if (! $purchaseOrder) {
            return $this->badRequestResponse("Could not get Purchase Order of id {$id}");
        }

        try {
            DB::beginTransaction();

            $assigner = auth()->user();
            $assignees = User::where('role', UserRole::FLOOR_TEAM)->get();

            $purchaseOrder->assignedMembers()->detach($assignees->pluck('id')->toArray());
            $purchaseOrder->status = JobProgress::CREATED;
            $purchaseOrder->save();

            $assigneesDetails = [];
            foreach ($assignees as $assignee) {
                $assigneesDetails[] = [
                    'id' => $assignee->id,
                    'name' => $assignee->username,
                ];
            }

            Notification::sendNow($assignees, new JobUnassigned($purchaseOrder, $assigner, $assigneesDetails));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Unable to perform this action at this time", exception: $e);
        }

        return $this->successResponse("$assigner->username unassigned this purchase order from floor team successfully", [
            'purchase_order' => new PurchaseOrderResource($purchaseOrder),
            'assinger' => $assigner,
        ]);
    }
}
