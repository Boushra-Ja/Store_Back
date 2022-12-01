<?php

namespace App\Http\Resources;

use App\Http\Resources\BoshraRe\ProductAllResource;
use App\Http\Resources\BoshraRe\ProductResource;
use App\Http\Resources\BoshraRe\RatingResource;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\DiscountProduct;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\RatingStore;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
namespace App\Http\ResourcesBat;

use App\Models\Customer;
use App\Models\DiscountProduct;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCollectionB extends JsonResource
{

    public function toArray($request)
    {

       // $customer_id = Customer::where('id' ,  $this->customer_id)->first()['persone_id'] ;
        $customer_id = Order::where('id' ,  $this->order_id)->first()['customer_id'] ;


        return  [

            'order_id' =>  $this->order_id ,
            'amount'=>$this->amount,
             'gift_order'=>$this->gift_order,
            'created_at'=>$this->created_at->format('Y/m/d'),
            '$customer_id'=>Order::where('id' , $this->order_id)->first()['customer_id'],
            'my_products' => OrderProductB::collection(Product::where('id' , $this->product_id)->get()),


        ];
    }
}
