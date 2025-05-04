<?php

namespace App\Actions\PurchaseOrder;

use App\Constants\JobProgress;
use App\Constants\UserRole;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Notifications\JobAssigned;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AssignMembersAction
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
            ->withCount('comments')
            ->find($id);

        if (! $purchaseOrder) {
            return $this->badRequestResponse("Could not get Purchase Order of id {$id}");
        }

        try {
            DB::beginTransaction();

            $assigner = auth()->user();
            $assigneesDetails = [];
            $allAssigneeIds = [];

            User::where('role', UserRole::FLOOR_TEAM)->chunk(100, function ($assignees) use (&$assigneesDetails, &$allAssigneeIds) {
                $allAssigneeIds = array_merge($allAssigneeIds, $assignees->pluck('id')->toArray());

                foreach ($assignees as $assignee) {
                    $assigneesDetails[] = [
                        'id' => $assignee->id,
                        'name' => $assignee->username,
                    ];
                }
            });

            $purchaseOrder->assignedMembers()->sync($allAssigneeIds);
            $purchaseOrder->status = JobProgress::IN_QUEUE;
            $purchaseOrder->save();

            Notification::sendNow(User::whereIn('id', $allAssigneeIds)->get(), new JobAssigned($purchaseOrder, $assigner, $assigneesDetails));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Unable to perform this action at this time", exception: $e);
        }


        return $this->successResponse("$assigner->username assigned this purchase order to floor team members successfully", [
            'purchase_order' => new PurchaseOrderResource($purchaseOrder),
            'assinger' => $assigner,
        ]);
    }
}
