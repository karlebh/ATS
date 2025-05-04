<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobProgressStatusUpdated extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(public $purchaseOrder, public $eventUpdater)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'purchaseOrder' => $this->purchaseOrder,
            'message' => $this->getMessage(),
        ];
    }

    private function getMessage()
    {
        return $this->eventUpdater->username . " updated the status of this purchase order, #{$this->purchaseOrder->po_number}. Please attend to it as soon as possible.";
    }
}
