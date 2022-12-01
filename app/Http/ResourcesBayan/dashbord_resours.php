<?php

namespace App\Http\ResourcesBayan;

use App\Http\Controllers\CollectionController;

use App\Http\Controllers\OrderController;

use App\Models\Order;

use App\Models\ProductRating;
use App\Models\RatingStore;
use App\Models\StoreVisitore;
use Illuminate\Http\Resources\Json\JsonResource;

class dashbord_resours extends JsonResource
{


    public function toArray($request)
    {

        $order_r = OrderController::orderstatus($this->resource, 1);
        $order_recev = count($order_r);
        $order_ac = OrderController::orderstatus($this->resource, 2);
        $order_accept = count($order_ac);
        $product = CollectionController::index($this->resource);
        $product_count = count($product);
        $order_delever = OrderController::orderstatus($this->resource, 3);

        $rate = RatingStore::where('store_id', '=', $this->resource)->get();
        $rate_count = count($rate);


        ///customer order total
        $order = Order::where('store_id', '=', $this->resource)->get();

        $d = $order->groupBy('customer_id');


        $customer = customerResource::collection($d);

        $a = array();
        $i = 0;
        foreach ($product as $value) {
            $s = ProductRating::where('product_id', '=', $value->id)->where('value','=','2')->get();
            $a[$i] = ["name" => $value->name, "count" => count($s),"image"=>$value->image];
            $i += 1;
        }
        $array = collect($a)->sortBy('count')->reverse()->take('4')->toArray();

        $h = OrderController::dash_bord_art($this->resource);

        $visit=StoreVisitore::where('store_id','=',$this->resource)->get();
        $visit_store=array();
        $i=0;
        foreach ($visit as $v){
            $visit_store[$i]=["date" => $v->visit_date, "count" => $v->visit_num];
            $i+=1;
        }




        return [
            'order_recev_count' => $order_recev,
            'order_accept_count' => $order_accept,
            'product_count' => $product_count,
            'rate_count' => $rate_count,
            'order_accept' => $order_ac,
            'order_delever' => $order_delever,
            'customer' => $customer,
            'rating_product' => $array,
            'salls' => $h,
            'visit'=>$visit_store
        ];
    }

}
