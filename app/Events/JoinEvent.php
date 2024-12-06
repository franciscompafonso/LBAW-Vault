<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JoinEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $event_name;

    /**
     * Create a new event instance.
     */
    public function __construct($event_name)
    {
        $this->event_name = $event_name;
        $this->message = 'You joined the ' . $event_name . ' event';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn() {
        return new Channel('event-channel');
    }    


    // You should specify the name of the generated notification.
    public function broadcastAs() {
        return 'notification-join-event';
    }
}

