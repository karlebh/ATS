<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use Illuminate\Notifications\Notification;

class CommentReplied extends Notification
{

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $commentOwner,
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
            'comment_id' => $this->comment->id,
        ];
    }

    private function getMessage()
    {
        return $this->commentOwner->username . " replied your comment. Kindly attend to it as soon as possible.";
    }
}
