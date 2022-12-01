<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Collection;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\Persone;
use App\Models\Product;
use App\Models\RatingStore;
use App\Models\StoreManager;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreTasneemResource extends JsonResource
{

    public function toArray($request)
    {
        $Collection = Collection::where('store_id' , $this->id) ->get();
        $sum = 0 ;
        foreach ($Collection as $val) {

            $sum = $sum + Product::where("collection_id",$val['id'])->count();
        }

        $orders_recieved = Order::where('store_id' , $this->id)
        ->where('status_id' , OrderStatus::where('status' , 'مسلم')->value('id'))->count();

        $orders_accepted = Order::where('store_id' , $this->id)
        ->where('status_id' , OrderStatus::where('status' , 'مقبول')->value('id'))->count();


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
            'discription' => $this->discription ,
            'status' => $this->status ,
            'email' => Persone::where('id' , StoreManager::where('store_id' , $this->id)->value('person_id'))->value('email') ,
            'num_products' => $sum,
            'orders_recieved' => $orders_recieved ,
            'orders_accepted' => $orders_accepted ,
            'review' =>  $rate,

        ];
    }
}
