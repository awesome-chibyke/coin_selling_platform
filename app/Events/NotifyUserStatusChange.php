<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyUserStatusChange implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payload;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }

    public function broadcastOn()
    {
        return new Channel('payments');
    }

    public function broadcastAs(){
        return 'notify-user-status-change';
    }
}