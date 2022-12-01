<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Chat;
use App\Models\Message;
use App\Events\ChatEvent;
use Illuminate\Http\Request;
use App\Events\NotificationEvent;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;

class MessageController extends Controller
{

    //bayan
    public function store(Request $request)
    {

        $resever_id = 0;
        $val = Chat::where('sender_id', '=', $request->sender_id)->where('receiver_id', '=', $request->receiver_id)->first();
        $val2 = Chat::where('sender_id', '=', $request->receiver_id)->where('receiver_id', '=', $request->sender_id)->first();

        if ($val || $val2) {
            if ($val) {
                $message = Message::create([
                    'sender_id' => $request->sender_id,
                    'message' => $request->message,
                    'chats_id' => $val->id,

                ]);
                $resever_id = $val->receiver_id;
            } else {
                $message = Message::create([
                    'sender_id' => $request->sender_id,
                    'message' => $request->message,
                    'chats_id' => $val2->id,

                ]);

                $resever_id = $val2->sender_id;

            }


        } else {
            $val = ChatController::store($request);

            $message = Message::create([
                'sender_id' => $request->sender_id,
                'message' => $request->message,
                'chats_id' => $val->id,

            ]);
            $resever_id = $val->receiver_id;

        }

        broadcast(new ChatEvent($request->message,$resever_id,$request->sender_id));
        broadcast(new NotificationEvent("chat",$resever_id,"title"));

        return $val;

    }

    //bayan
    public function index($chat_id, $number)
    {


        $chats = Message::where('chats_id', '=', $chat_id)->orderBy('created_at', 'desc')->get();
       // $val = ChatController::store($request);

        $c = $chats->take(20*$number);
        $rr = $c->sortBy('created_at');
       $num=count($rr);


        return response()->json([
            'data' => $rr,
            'num' => $num
        ], 200);
    }

    public function chatt($sender_id,$receiver_id){
        $chats=Chat::where('sender_id','=',$sender_id)->where('receiver_id','=',$receiver_id)->value('id');

        return $chats;

    }

}
