<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductRating;

use App\Models\SecondrayClassificationProduct;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        $good = ProductRating::where('product_id' , $this->id) ->where('value', 2)->count();
        $bad = ProductRating::where('product_id' , $this->id) ->where('value', 0)->count();
        $smile = ProductRating::where('product_id' , $this->id) ->where('value', 1)->count();

        $num_of_review = max($smile , $bad , $good);
        if($num_of_review == $good )
            $review_value = 2 ;
        else if($num_of_review == $smile )
            $review_value = 1 ;
        else if($num_of_review == $bad )
            $review_value = 0 ;


        return  [
            'product_id' => $this->id,
            'product_name' => $this->name,
            'image' => $this->image,
            'selling_price' => $this->selling_price,
            'store_id' => Store::where('id', Collection::where('id', $this->collection_id)->value('store_id'))->value('id'),
            'store_name' => Store::where('id', Collection::where('id', $this->collection_id)->value('store_id'))->value('name'),
            'num_salling_store' => Store::where('id', Collection::where('id', $this->collection_id)->value('id'))->value('num_of_salling'),
            'review_value' => $review_value ,
            'num_of_review' => $num_of_review

        ];
    }
}
