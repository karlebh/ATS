<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    use ResponseTrait;

    public function getUnreadNotifications()
    {
        $user = auth()->user();

        return NotificationResource::collection(
            $user->unreadNotifications()->latest()->get()
        );
    }

    public function getReadNotifications()
    {
        $user = auth()->user();

        return NotificationResource::collection($user->readNotifications()->latest()->get());
    }

    public function getAllNotifications()
    {
        $user = auth()->user();

        return NotificationResource::collection($user->notifications()->latest()->get());
    }

    public function deleteNotification(int $id)
    {
        $user = auth()->user();

        $notification = $user->notifications()->where('id', $id)->first();

        if (! $notification) {
            return $this->errorResponse('Notification not found', 404);
        }

        $notification->delete();

        return $this->successResponse('Notification deleted successfully');
    }

    public function markAsRead(string $id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return $this->errorResponse('Notification not found', 404);
        }

        if ($notification->read_at) {
            return $this->errorResponse('Notification is already read', 400);
        }

        $notification->markAsRead();

        return $this->successResponse('Notification marked as read successfully');
    }

    public function markAllAsRead()
    {
        $user = auth()->user();

        if ($user->unreadNotifications->isEmpty()) {
            return $this->errorResponse('All notifications are already read', 200);
        }

        $user->unreadNotifications->markAsRead();

        return $this->successResponse('All unread notifications marked as read successfully');
    }
}
