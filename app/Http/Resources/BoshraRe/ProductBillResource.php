<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Discount;
use App\Models\DiscountProduct;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductBillResource extends JsonResource
{

    public function toArray($request)
    {
        return [

            'order_product' => $this->id ,
            'product_id' => $this->product_id ,
            'product_name' => Product::where('id' ,$this->product_id )->value('name'),
            //'quantity' => $this->quantity ,
            'selling_price' => Product::where('id' ,$this->product_id )->value('selling_price'),
            'discount_value' => Discount::where('id' , DiscountProduct::where('id' , Product::where('id' , $this->product_id)->value('discount_products_id'))->value('discounts_id')) ->value('value'),

        ] ;
    }
}
