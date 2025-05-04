<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentCreated extends Notification
{
    public $commentable = '';

    public function __construct(public $commentOwner, public $comment)
    {
        $this->commentable = '';

        if ($this->comment->commentable instanceof PurchaseOrder) {
            $this->commentable = 'purchase-order';
        } elseif ($this->comment->commentable instanceof Task) {
            $this->commentable = 'task';
        };
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'commentOwner' => $this->commentOwner,
            $this->commentable => $this->comment->commentable,
            $this->commentable . "_id" => $this->comment->commentable->id,
            'message' => $this->getMessage(),
        ];
    }

    private function getMessage()
    {
        $number = '';
        if ($this->comment->commentable instanceof PurchaseOrder) {
            $number = $this->comment->commentable->po_number;
        }
        return $this->commentOwner->username . " left a comment on a {$this->commentable} #{$number} . Please attend to it as soon as possible.";
    }
}
