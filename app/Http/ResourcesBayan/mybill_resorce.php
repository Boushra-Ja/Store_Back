<?php

namespace App\Http\ResourcesBayan;

use App\Models\Customer;
use App\Models\StoreManager;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Discount;
use App\Models\DiscountCode;
use App\Models\DiscountProduct;
use App\Models\Persone;
use App\Models\Product;
use App\Models\Store;


class mybill_resorce extends JsonResource
{


    public function toArray($request)
    {
        $store = Store::where('id', '=', $this->store_id)->first();
        $person = Persone::where('id', '=', Customer::where('id','=',$this->customer_id)->value('persone_id'))->first();
        $product = Product::where('id', '=', $this->product_id)->first();

        return [
            'order_id' => $this->order_id,


            'delivery_time' => $this->delivery_time,
            'store_name' => $store->name,
            'customer_name' => $person->name,
            'customer_email' => $person->email,

            'product_name' => $product->name,
            'selling_price' => $product->selling_price,
            'discount_codes' => Discount::where('id', '=', DiscountCode::where('id', '=', $this->discount_codes_id)->value('discounts_id'))->value('value'),
            'discount_products' => Discount::where('id', '=', DiscountProduct::where('id', '=', $this->discount_products_id)->value('discounts_id'))->value('value'),


        ];
    }
}
