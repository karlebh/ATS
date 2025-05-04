<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobUnassigned extends Notification
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
        return $this->assigner->username . " has unassigned a purchase order, po_number: #{$this->purchaseOrder->po_number}, from all floor team members.";
    }
}
