<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InviteSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event_id;
    public $message;

    public function __construct($event_id, $message)
    {
        $this->event_id = $event_id;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('event.' . $this->event_id);
    }

    public function broadcastAs()
    {
        return 'invite-sent';
    }
}
