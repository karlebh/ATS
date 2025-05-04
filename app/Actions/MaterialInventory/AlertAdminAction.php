<?php

namespace App\Actions\MaterialInventory;

use App\Constants\UserRole;
use App\Mail\AdminAlerted;
use App\Models\MaterialInventory;
use App\Models\User;
use App\Notifications\AlertAdmin;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AlertAdminAction
{
    use ResponseTrait;

    public function execute(array $requestData)
    {
        $adminUsers = User::whereRole(UserRole::ADMIN)->get();

        if ($adminUsers) {
            $adminUsers->chunk(50)->each(function ($chunk) use ($adminUsers, $requestData) {
                Notification::sendNow(
                    $chunk,
                    new AlertAdmin(
                        $adminUsers,
                        auth()->user(),
                        $requestData,
                    )
                );
            });
        }

        try {
            Mail::to($adminUsers)->send(new AdminAlerted($requestData, auth()->user()));
        } catch (\Exception $exception) {
            $this->serverErrorResponse('Failed to send task assignment mail', $exception);
        }

        return $this->successResponse("Admin Alert Sent Successfully");
    }
}
