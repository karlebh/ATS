<?php

namespace App\Events;

use App\Http\Resources\PurchaseOrderResource;
use DateTime;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderJobProgress implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public $purchaseOrder, public $user)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('job-status'),
            new PresenceChannel('job-status'),
            new Channel('job-status'),
        ];
    }

    public function broadcastAs()
    {
        return 'job-status-update';
    }

    public function broadcastWith(): array
    {
        $purchaseOrder = $this->purchaseOrder->refresh();

        return [
            'status' => $purchaseOrder->status,
            'purchase_order' => new PurchaseOrderResource($purchaseOrder),
            'user' => $this->user,
            'message' => 'The user object above made the update',
        ];
    }
}
