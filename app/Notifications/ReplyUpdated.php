<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReplyUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $commentOwner,
        public $commentable,
        public $comment,
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
            'comment' => $this->comment,
            'commentOwner' => $this->commentOwner,
            'message' => $this->getMessage(),
        ];
    }

    private function getMessage()
    {
        return $this->commentOwner->username . " updated their reply to your comment. Please attend to it as soon as possible.";
    }
}
