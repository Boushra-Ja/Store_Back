<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Persone;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //bayan
    public static function store(Request $request)
    {
        $chat = Chat::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
        ]);

        return $chat;
    }


    //bayan
    public function index($sender_id)
    {

        $recever = 0;
        $chats = Chat::where('sender_id', '=', $sender_id)->orWhere('receiver_id', '=', $sender_id)->orderBy('created_at', 'asc')->get();

        $a = array();
        $i = 0;
        foreach ($chats as $chat) {
            if ($chat->sender_id == $sender_id) {
                $person = Persone::where('id', '=', $chat->receiver_id)->first();

                $recever = $chat->receiver_id;
            } else {
                $person = Persone::where('id', '=', $chat->sender_id)->first();
                $recever = $chat->sender_id;

            }

            $date=Message::where('chats_id',$chat->id)->orderBy('created_at', 'desc')->value('created_at');
            $a[$i] = [
                'chat_id' => $chat->id,
                'receiver_id' => $recever,
                'image' => $person->image,
                'name' => $person->name,
            ];
            $i += 1;
        }

        return response()->json([
            'data' => $a,
        ], 200);

    }


    //bayan
    public function singelChat(Request $request)
    {

        $chat = Chat::where('sender_id', '=', $request->sender_id)->where('receiver_id', '=', $request->receiver_id)->first();
        if ($chat)
            $person = Persone::where('id', '=', $chat->receiver_id)->first();
        else {
            $chat = $this::store($request);
            $person = Persone::where('id', '=', $chat->receiver_id)->first();

        }

        $date=Message::where('chats_id',$chat->id)->orderBy('created_at', 'desc')->value('created_at');

        return response()->json([
            'chat_id' => $chat->id,
            'receiver_id' => $request->receiver_id,
            'image' => $person->image,
            'name' => $person->name,
            'updated_at'=>$date->format('Y-m-d '),
        ], 200);

    }


}
