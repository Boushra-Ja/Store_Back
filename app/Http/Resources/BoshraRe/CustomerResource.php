<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Classification;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\Persone;
use App\Models\SecondrayClassification;
use App\Models\SecondrayClassificationProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{

    public function toArray($request)
    {
        $orders = Order::where("customer_id",$this->id)->get() ;
        $classifications = array() ;
        $i =0 ;
        $total_price = 0 ;
        foreach ($orders as $value) {
            if($value['status_id'] == OrderStatus::where('status' , 'مسلم')->value('id'))
            {

                $total_price = $total_price + $value['delivery_price'] ;
            }
            $order_products =  OrderProduct::where('order_id' , $value['id'])->get() ;
            foreach ($order_products as $val) {
                $temp = Classification::where('id' ,SecondrayClassification::where('id' , SecondrayClassificationProduct::where('product_id' , $val['product_id'])->value('secondary_id'))->value('classification_id'))->value('title') ;
                if(!in_array($temp , $classifications))
                {
                    $classifications[$i] = $temp ;
                    $i++;
                }

            }
        }



        return [

            'customer_id' => $this->id ,
            'customer_name' => Persone::where('id' , $this->persone_id)->value('name'),
            'created_at' => $this->created_at->format('Y-m-d '),
            'num_orders'  =>Order::where("customer_id",$this->id)->count(),
            'num_orders_recived'  =>Order::where("customer_id",$this->id)->where('status_id' , OrderStatus::where('status' , 'مسلم')->value('id'))->count(),
            'total_price' => $total_price,
            'email' =>Persone::where('id' , Customer::where('id' , $this->id)->value('persone_id'))->value('email') ,
            'classifications' => $classifications ,

        ];
    }
}
