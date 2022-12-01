<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Store;
use App\Models\StoreManager;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{

    public function toArray($request)
    {


        return [
            'id' => $this->id ,
            'title' => $this->title ,
            'message' => $this->message ,
            'sender_id' => $this->sender_id ,
            'receiver_id' => $this->receiver_id ,
            'created_at' => $this->created_at->format('Y-m-d'),
            'store_id' => StoreManager::where('person_id' , $this->sender_id)->value('store_id') ,
            'store_image' => Store::where('id' , StoreManager::where('person_id' , $this->sender_id)->value('store_id'))->value('image') ,
        ];
    }
}
