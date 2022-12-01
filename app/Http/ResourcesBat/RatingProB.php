<?php

namespace App\Http\ResourcesBat;

use App\Models\ProductRating;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingProB extends JsonResource
{

    public function toArray($request)
    {
        return  [

            'id' =>$this->id,
            'image' => $this->image ,
            'name' => $this->name ,
            'selling_price' =>$this->selling_price ,
            'cost_price' => $this->cost_price ,
            'number_of_sales' => $this->number_of_sales ,
            'rating' => RatingB::collection(ProductRating::where('product_id', $this->id)->get()),



        ];
    }
}
