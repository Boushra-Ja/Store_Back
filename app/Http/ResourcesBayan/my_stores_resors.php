<?php

namespace App\Http\ResourcesBayan;

use App\Http\Resources\BoshraRe\ProductResource;
use App\Http\Resources\BoshraRe\RatingResource;
use App\Models\Collection;
use App\Models\Product;
use App\Models\RatingStore;
use Illuminate\Http\Resources\Json\JsonResource;


class my_stores_resors extends JsonResource
{


    public function toArray($request)
    {
        return  [
            'name' => $this->name ,
            'num_cell' => $this->num_of_salling ,
            'image' => $this->image ,
            'Brand'=>$this->Brand,
            'discription' => $this->discription ,
            'facebook' => $this->facebook ,
            'status' => $this->status ,
            'delivery_area'=>$this->delivery_area,

            'review' => RatingResource::collection(RatingStore::where('store_id' , $this->id)->get()) ,
            'my_products' => ProductResource::collection(Product::where('id' , Collection::where('store_id' , $this->id)->value('id'))->get())

        ];
    }
}
