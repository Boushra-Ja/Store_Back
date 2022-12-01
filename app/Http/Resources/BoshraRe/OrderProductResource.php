<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{

    public function toArray($request)
    {
        return [

            'order_product_id' => $this->id ,
            'order_id' => $this->order_id ,
            'product_id' => $this->product_id ,
            'product_name' => Product::where('id' , $this->product_id)->value('name') ,
            'product_image' => Product::where('id' , $this->product_id)->value('image') ,
            'status_id' => $this->status_id ,
            'store_id' => Order::where('id' , $this->order_id )->value('store_id') ,
            'store_name' => Store::where('id' ,  Order::where('id' , $this->order_id )->value('store_id'))->value('name') ,
            'store_image' => Store::where('id' ,  Order::where('id' , $this->order_id )->value('store_id'))->value('image') ,
            'delivery_time' => Order::where('id' , $this->order_id )->value('delivery_time') ,
            'order_time' => Order::where('id' , $this->order_id)->value('created_at')->format('Y-m-d'),
            'gift_order' => $this->gift_order ,
            'amount' => $this->amount
        ];
    }
}
