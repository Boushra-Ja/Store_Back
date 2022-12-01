<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Collection;
use App\Models\Persone;
use App\Models\Product;
use App\Models\RatingStore;
use App\Models\StoreManager;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'email' => Persone::where('id' , StoreManager::where('store_id' , $this->id)->value('person_id'))->value('email') ,
            'discription' => $this->discription ,
            'facebook' => $this->facebook ,
            'mobile' => $this->mobile ,
            'status' => $this->status ,
            'delivery_area' => $this->delivery_area,
            'rate' => $rate ,
            'review' => $rating,
            'brand' => $this->Brand ,

            'my_products' => ProductResource::collection(Product::where('id' , Collection::where('store_id' , $this->id)->value('id'))->get()),

        ];
    }
}
