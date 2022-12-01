<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Customer;
use App\Models\Persone;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{


    public function toArray($request)
    {
        return [
            'customer_id' => $this->customer_id,
            'customer_name' => Persone::where('id','=',Customer::where('id','=',$this->customer_id)->value('persone_id'))->value('name'),
            'order_id' => $this->id,
            'delivery_time' => $this->delivery_time,
            'delivery_price' => $this->delivery_price,
            'store_id' => $this->store_id,
            'store_name' => Store::where('id' ,$this->store_id )->value('name'),
            'store_image' => Store::where('id' ,$this->store_id )->value('image'),
            'status_id' => $this->status_id ,
            'created_at' => $this->created_at->format('Y-m-d '),
            'discount_codes_id' => $this->discount_codes_id
        ];
    }
}
