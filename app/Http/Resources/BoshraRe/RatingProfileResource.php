<?php

namespace App\Http\Resources\BoshraRe;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\ProductRating;
use App\Models\RatingStore;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingProfileResource extends JsonResource
{


    public function toArray($request)
    {
        $date = \Carbon\Carbon::now();
        $lastMonth =  $date->subMonth()->format('m');
        $lastMonth2 =  $date->subMonth()->format('m');
        $lastMonth3 =  $date->subMonth()->format('m');

        //////////////////last month
        $num_sales_last_month = Order::where('store_id' , $this->store_id)
        ->where('status_id' , OrderStatus::where('status' , 'مسلم')->value('id'))
        ->whereMonth('delivery_time', '=', $lastMonth)
        ->count();

        $orders = Order::where('store_id' , $this->store_id)
        ->where('status_id' , OrderStatus::where('status' , 'مسلم')->value('id'))
        ->whereMonth('delivery_time', '=', $lastMonth)->get() ;

        $good =0 ;
        $bad = 0 ;
        $smile = 0 ;
        foreach ($orders as  $value) {
            $order_products = OrderProduct::where('order_id' , $value['id'])->get() ;
            foreach ($order_products  as $val) {
                $good = $good +  ProductRating::where('product_id' , $val['product_id']) ->where('value', 2)->count();
                $bad = $bad + ProductRating::where('product_id' , $val['product_id']) ->where('value', 0)->count();
                $smile = $smile +  ProductRating::where('product_id' ,$val['product_id']) ->where('value', 1)->count();
            }
        }


        ////////////befor 2 month
        $num_sales_befor_2month = Order::where('store_id' , $this->store_id)
        ->where('status_id' , OrderStatus::where('status' , 'مسلم')->value('id'))
        ->whereMonth('delivery_time', '=', $lastMonth2)
        ->count();

        $orders2 = Order::where('store_id' , $this->store_id)
        ->where('status_id' , OrderStatus::where('status' , 'مسلم')->value('id'))
        ->whereMonth('delivery_time', '=', $lastMonth2)->get() ;

        $good2 =0 ;
        $bad2 = 0 ;
        $smile2 = 0 ;
        foreach ($orders2 as  $value) {
            $order_products = OrderProduct::where('order_id' , $value['id'])->get() ;
            foreach ($order_products  as $val) {
                $good2 = $good2 +  ProductRating::where('product_id' , $val['product_id']) ->where('value', 2)->count();
                $bad2 = $bad2 + ProductRating::where('product_id' , $val['product_id']) ->where('value', 0)->count();
                $smile2 = $smile2 +  ProductRating::where('product_id' ,$val['product_id']) ->where('value', 1)->count();
            }
        }

        ///////before 3 month
        $num_sales_befor_3month = Order::where('store_id' , $this->store_id)
        ->where('status_id' , OrderStatus::where('status' , 'مسلم')->value('id'))
        ->whereMonth('delivery_time', '=', $lastMonth3)
        ->count();

        $orders3 = Order::where('store_id' , $this->store_id)
        ->where('status_id' , OrderStatus::where('status' , 'مسلم')->value('id'))
        ->whereMonth('delivery_time', '=', $lastMonth3)
        ->get() ;

        $good3 =0 ;
        $bad3 = 0 ;
        $smile3 = 0 ;
        foreach ($orders3 as  $value) {
            $order_products = OrderProduct::where('order_id' , $value['id'])->get() ;
            foreach ($order_products  as $val) {
                $good3 = $good3 +  ProductRating::where('product_id' , $val['product_id']) ->where('value', 2)->count();
                $bad3 = $bad3 + ProductRating::where('product_id' , $val['product_id']) ->where('value', 0)->count();
                $smile3 = $smile3 +  ProductRating::where('product_id' ,$val['product_id']) ->where('value', 1)->count();
            }
        }

        $rating = RatingResource::collection(RatingStore::where('store_id' , $this->store_id)->get()) ;
        $s = 0 ;
        foreach ($rating as  $value) {
            $s = $s + $value['value'] ;
        }
        if(sizeof($rating) != 0 )
            $rate = $s/sizeof($rating);
        else{
            $rate =0 ;
        }

        $h = OrderController::dash_bord_art($this->store_id);

        ////////////////////
        return [
            'store_name' => Store::where('id' , $this->store_id )->value('name') ,
            'created_at' => Store::where('id' , $this->store_id )->value('created_at')->format('Y-m-d ') ,
            'status' => Store::where('id' , $this->store_id )->value('status') ,
            'delivery_area' => Store::where('id' , $this->store_id )->value('delivery_area') ,
            'image' => Store::where('id' , $this->store_id )->value('image') ,
            'brand' => Store::where('id' , $this->store_id )->value('brand') ,
            'review'=>$rate ,
            'salles' =>$h ,
            'num_sales_last_month' => $num_sales_last_month,
            'num_sales_befor_2month' => $num_sales_befor_2month,
            'num_sales_befor_3month' => $num_sales_befor_3month ,
            'good_last_month' => $good ,
            'bad_last_month' => $bad ,
            'smile_last_month' =>$smile,
            'good_last_2month' => $good2 ,
            'bad_last_2month' => $bad2,
            'smile_last_2month' =>$smile2,
            'good_last_3month' => $good3 ,
            'bad_last_3month' => $bad3,
            'smile_last_3month' =>$smile3

        ];
    }

    function additional( $data)
    {
        return parent::additional($data);
    }

}
