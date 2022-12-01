<?php

namespace App\Http\Resources\BoshraRe;

use App\Http\Controllers\ProductController;
use App\Http\Resources\OrderDiscountB;
use App\Models\Collection;
use App\Models\Discount;
use App\Models\DiscountProduct;
use App\Models\ProductRating;
use App\Models\RatingOrder;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductAllResource extends JsonResource
{

    public function toArray($request)
    {


        return  [
            'product_id' => $this->id,
            'product_name' => $this->name,
            'image' => $this->image,
            'selling_price' => $this->selling_price,
            'num_cell' => $this->number_of_sales,
            'prepration_time' => $this->prepration_time,
            'party' => $this->party,
            'discription' => $this->discription,
            'age' => $this->age,
            'cost_price' => $this->cost_price,
            //  'created_at' => $this->created_at->format('Y-m-d '),
            //  'updated_at' => $this->updated_at->format('Y-m-d '),
            'return_or_replace' => $this->return_or_replace,
            'discount_products_id' => $this->discount_products_id,
            'store_id' => Store::where('id', Collection::where('id', $this->collection_id)->value('store_id'))->value('id'),
            'store_name' => Store::where('id', Collection::where('id', $this->collection_id)->value('store_id'))->value('name'),
            'store_image' => Store::where('id', Collection::where('id', $this->collection_id)->value('store_id'))->value('image'),
            'num_salling_store' => Store::where('id', Collection::where('id', $this->collection_id)->value('store_id'))->value('num_of_salling'),
            'review' => RatingResource::collection(ProductRating::where('product_id', $this->id)->get()),
            'discount_value' => Discount::where('id' , DiscountProduct::where('id' , $this->discount_products_id)->value('discounts_id'))->value('value'),
            'discount_id'=>$this->discount_products_id


    ];
    }
}
