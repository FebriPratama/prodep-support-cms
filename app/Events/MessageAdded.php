<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $thread;
    public $threadid;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message,$threadid,$thread)
    {
        $this->message = $message;
        $this->thread =  $thread;
        $this->threadid = $threadid;
    }

    public function broadcastOn()
    {
      return new PrivateChannel('App.Chat.'.$this->threadid);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
/*    public function broadcastAs()
    {
        return 'user.message.new';
    }*/

}
