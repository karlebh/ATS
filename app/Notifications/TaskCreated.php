<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCreated extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(public $task)
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
            'task' => $this->task,
            'message' => $this->getMessage(),
        ];
    }

    private function getMessage()
    {
        return $this->task->user->name . " created a task for you.
            Please attend to it as soon as possible. po_number: #{$this->task->purchaseOrder->po_number}, task_name: {$this->task->name}";
    }
}
