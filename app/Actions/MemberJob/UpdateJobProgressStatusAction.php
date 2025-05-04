<?php

namespace App\Actions\MemberJob;

use App\Constants\JobProgress;
use App\Constants\UserRole;
use App\Events\JobProgressStatusUpdated;
use App\Events\PurchaseOrderJobProgress;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Notifications\JobProgressStatusUpdated as NotificationsJobProgressStatusUpdated;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Notification;

class UpdateJobProgressStatusAction
{
    use ResponseTrait;

    public function execute(array $requestData, int $id)
    {
        $purchaseOrder = PurchaseOrder::with('user')->find($id);

        if (! $purchaseOrder) {
            return $this->notFoundResponse("Purchase order with id: {$id} not found");
        }

        if ($requestData['status'] == JobProgress::COMPLETED) {
            $purchaseOrder->end_date = now();
        }
        $purchaseOrder->status = $requestData['status'];
        $purchaseOrder->save();

        // event(new PurchaseOrderJobProgress($purchaseOrder, auth()->user()));

        $adminUsers = User::whereRole(UserRole::ADMIN)->get();

        if ($adminUsers) {
            Notification::sendNow(
                $adminUsers,
                new NotificationsJobProgressStatusUpdated($purchaseOrder, auth()->user())
            );
        }

        return $this->successResponse(
            "Purchase order status updated successfully",
            [
                'purchase_order' => new PurchaseOrderResource($purchaseOrder)
            ]
        );
    }
}
