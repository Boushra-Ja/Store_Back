<?php

namespace App\Http\ResourcesBat;

use App\Http\Resources\BoshraRe\RatingResource;
use App\Models\Collection;
use App\Models\Discount;
use App\Models\DiscountProduct;
use App\Models\ProductRating;
use App\Models\RatingStore;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductB extends JsonResource
{

    public function toArray($request)
    {
        return  [

            'product_id' => $this->id ,
            'value_discount'=>  Discount::where('id' , DiscountProduct::where('id' , $this->discount_products_id)->value('discounts_id'))->value('value'),
            'product_name' => $this->name ,
            'image' => $this->image ,
            'selling_price' => $this->selling_price,
            'store_id' => Store::where('id' , Collection::where('id' , $this->collection_id )->value('store_id'))->value('id'),
            'store_name' => Store::where('id' , Collection::where('id' , $this->collection_id )->value('store_id'))->value('name'),
            'num_salling_store' => Store::where('id' , Collection::where('id' , $this->collection_id )->value('store_id'))->value('num_of_salling'),
            'review' => RatingB::collection(ProductRating::where('product_id', $this->id)->get()),
            'num_cell' => $this->number_of_sales,
            'cost_price' => $this->cost_price,
            'reviews' => RatingB::collection(RatingStore::where('store_id' , Store::where('id' , Collection::where('id' , $this->collection_id )->value('store_id'))->value('id'))->get()) ,


        ];
    }
}
