<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\DiscountCode;
use App\Models\DiscountProduct;
use App\Models\Order;
use App\Models\Persone;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'order_id' => $this->id ,
            'customer_id' => $this->customer_id ,
            'store_id' => $this->store_id ,
            'delivery_time' => $this->delivery_time,
            'store_name' => Store::where('id' ,  $this->store_id)->value('name') ,
            'store_image' => Store::  where('id' ,  $this->store_id)->value('image'),
            'customer_name' => Persone::where('id' , Customer::where('id' , $this->customer_id)->value('persone_id'))->value('name'),
            'order_product_id' => $this->id ,
            'product_id' => $this->product_id ,
            'product_name' => Product::where('id' ,$this->product_id )->value('name'),
            'amount' => $this->amount ,
            'selling_price' => Product::where('id' ,$this->product_id )->value('selling_price'),
            'discount_value' => Discount::where('id' , DiscountProduct::where('id' , Product::where('id' , $this->product_id)->value('discount_products_id'))->value('discounts_id')) ->value('value'),
            'discount_customer_value' => Discount::where('id' , DiscountCode::where('id' , Order::where('id' , $this->id)->value('discount_codes_id'))->value('discounts_id'))->value('value'),

        ];
    }
}
