<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(public $task)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
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
        return $this->task->user->name . " assigned a task to you. Please attend to it as soon as possible. po_number: #{$this->task->purchaseOrder->po_number}, task_name: {$this->task->name}";
    }
}
