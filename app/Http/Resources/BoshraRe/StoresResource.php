<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\RatingStore;
use Illuminate\Http\Resources\Json\JsonResource;

class StoresResource extends JsonResource
{

    public function toArray($request)
    {
        $rating = RatingResource::collection(RatingStore::where('store_id' , $this->id)->get()) ;
        $s = 0 ;
        foreach ($rating as  $value) {
            $s = $s + $value['value'] ;
        }
        if(sizeof($rating) != 0 )
            $rate = $s/sizeof($rating);
        else{
            $rate =0 ;
        }
        return  [
            'store_id' => $this->id ,
            'shop_name' => $this->name ,
            'num_cell' => $this->num_of_salling ,
            'image' => $this->image ,
            'brand' => $this->Brand ,
            'status' => $this->status ,
            'review' => RatingResource::collection(RatingStore::where('store_id' , $this->id)->get()) ,
            'rate' => $rate
        ];
}
}
