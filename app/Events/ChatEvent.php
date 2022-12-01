<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements  ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $resever_id;
    public $sender_id;

    public function __construct($message,$resever_id,$sender_id)
    {
        $this->message=$message;
        $this->resever_id=$resever_id;
        $this->sender_id=$sender_id;

    }


    public function broadcastOn()
    {
        return new Channel('public-channel.'.$this->resever_id.'.'.$this->sender_id);
    }

    public function  broadcastAs() :String{

        return 'ChatEvent';
    }
}
