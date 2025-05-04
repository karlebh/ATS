<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobAssigned extends Notification
{
    public function __construct(
        public $purchaseOrder,
        public $assigner,
        public $assigneesDetails,
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'assigner' => $this->assigner,
            'assigneesDetails' => $this->assigneesDetails,
            'message' => $this->getMessage(),
        ];
    }

    private function getMessage()
    {

        return $this->assigner->username . " assigned a purchase order, #{$this->purchaseOrder->po_number},  to all floor team members. Please attend to it as soon as possible.";
    }
}
