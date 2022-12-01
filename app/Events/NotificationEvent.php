<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements  ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user_id;
    public $title;

    public function __construct($message,$user_id,$title)
    {
        $this->message=$message;
        $this->user_id=$user_id;
        $this->title=$title;
    }


    public function broadcastOn()
    {
        return new Channel('public-channel.'.$this->user_id);
    }

    public function  broadcastAs() :String{

        return 'NotificationEvent';
    }

}

