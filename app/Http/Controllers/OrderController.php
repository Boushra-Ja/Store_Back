<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\ResourcesBayan\myorderResource;
use App\Models\Discount;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\BoshraRe\OrdersResource;
use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\Persone;
use App\Models\Store;
use App\Models\StoreManager;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{

    /////جميع الطلبات
    ///boshra
    public function index()
    {
        $orders = Order::all();
        if ($orders) {
            return $this->sendResponse($orders, "sucess");
        }

        return $this->sendErrors([], 'failed');
    }

    ////////التأكد أن الطلب موجود
    //boshra
    public function check_of_order($customer_id, $store_id)
    {

        $data = Order::where('customer_id', Customer::where('persone_id' , $customer_id)->value('id'))->where('store_id', $store_id)->first();
        if ($data) {
            return $data->id;
        }
        return 0;
    }


    //boshra
    public function store(StoreOrderRequest $request)
    {

        $code = DiscountCode::where('discounts_id', '=', Discount::where('store_id', '=', $request->store_id)->where('type' , 2)->where('value', '=', 0)->value('id'))->value('id');

            $order = Order::Create([
                'store_id' => $request->store_id,
                'customer_id' => Customer::where('persone_id' , $request->customer_id)->value('id'),
                'status_id' => OrderStatus::where('status' , 'معلق')->value('id'),
                'delivery_time' => $request->delivery_time,
                'delivery_price' => $request->delivery_price,
                'discount_codes_id' => $code
        ]);

        return $this->sendResponse(OrdersResource::collection([$order]), 'success');
    }


    //////////جميع الطلبات المقبولة
    ///boshra
    public function acceptence_orders($customer_id)
    {
        $orders = Order::where('customer_id', Customer::where('persone_id' , $customer_id)->value('id'))
        ->where('status_id', OrderStatus::where('status' , 'مقبول')->value('id'))->get();

        return $this->sendResponse(OrdersResource::collection($orders), 'successs');
    }

    //////الطلبات المعلقة
    ///boshra
    public function waiting_orders($customer_id)
    {

        $orders = Order::where('customer_id', Customer::where('persone_id' , $customer_id)->value('id'))
        ->where('status_id', OrderStatus::where('status' , 'معلق')->value('id'))->get();

        return $this->sendResponse(OrdersResource::collection($orders), 'successs');

    }

    ///الطلبات المسلمة
    ///boshra
    public function received_orders($customer_id)
    {

        $orders = Order::where('customer_id', Customer::where('persone_id' , $customer_id)->value('id'))
        ->where('status_id', OrderStatus::where('status' , 'مسلم')->value('id'))->get();

        return $this->sendResponse(OrdersResource::collection($orders), 'successs');

    }


    ///عرض الطلبات
    /// bayan
    public static function orderstatus($store_id, $id)
    {
        if ($id == 1) {
            $s = OrderStatus::where('status', '=', 'معلق')->value('id');
        } else if ($id == 2) {
            $s = OrderStatus::where('status', '=', 'مقبول')->value('id');
        } else if ($id == 3) {
            $s = OrderStatus::where('status', '=', 'مسلم')->value('id');
        }

        $g = OrderController::all_my_order($store_id, $s);

        return $g;


    }
    //الطلبات المعلقة/المنفذة/المقبولة لمتجر
    //bayan
    public static function all_my_order($id, $i)
    {

        $order = DB::table('orders')->join('order_products', function ($join) use ($i) {
            $join->on('order_products.order_id', '=', 'orders.id')->where('order_products.status_id', '=', $i);
        })->where('orders.store_id', '=', $id)->get();


        $o = $order->groupBy('order_id');
        $i = 0;


        $g = array();
        foreach ($o as $v) {
            foreach ($v as $value) {

                $g[$i] = myorderResource::make($value);
                $i += 1;
                break;

            }
        }


        return $g;
    }

    //bayan
    public static function dash_bord_art($store_id)
    {
        $s = OrderStatus::where('status', '=', 'مسلم')->value('id');

        $order = DB::table('orders')->join('order_products', function ($join) use ($s) {
            $join->on('order_products.order_id', '=', 'orders.id')->where('order_products.status_id', '=', $s);
        })->where('orders.store_id', '=', $store_id)->get();

        $i=date("Y");

        $g = array();
        $g[0] = $order->whereBetween('delivery_time', [$i.'-01-02', $i.'-02-1'])->count();
        $g[1] = $order->whereBetween('delivery_time', [$i.'-02-02', $i.'-03-01'])->count();
        $g[2] = $order->whereBetween('delivery_time', [$i.'-03-02', $i.'-04-01'])->count();
        $g[3] = $order->whereBetween('delivery_time', [$i.'-04-02', $i.'-05-01'])->count();
        $g[4] = $order->whereBetween('delivery_time', [$i.'-05-02', $i.'-06-01'])->count();
        $g[5] = $order->whereBetween('delivery_time', [$i.'-06-02', $i.'-07-01'])->count();
        $g[6] = $order->whereBetween('delivery_time', [$i.'-07-02', $i.'-08-01'])->count();
        $g[7] = $order->whereBetween('delivery_time', [$i.'-08-02', $i.'-09-01'])->count();
        $g[8] = $order->whereBetween('delivery_time', [$i.'-09-02', $i.'-10-01'])->count();
        $g[9] = $order->whereBetween('delivery_time', [$i.'-10-02', $i.'-11-01'])->count();
        $g[10] = $order->whereBetween('delivery_time', [$i.'-11-02', $i.'-12-01'])->count();
        $g[11] = $order->whereBetween('delivery_time', [$i.'-12-02', $i.'-1-01'])->count();


        return $g;
    }


    //bayan
    public function accept_order($id)
    {
        $s = OrderStatus::where('status', '=', 'مقبول')->value('id');
        $orderr=Order::where('id','=',$id)->first();
        $orderr->update(['status_id'=>$s]);
        $order = OrderProduct::where('order_id', '=', $id)->get();
        foreach ($order as $value) {
            $value->update(['status_id' => $s]);
        }

        $store=Store::where('id',$orderr->store_id)->value('name');
        $person_s=StoreManager::where('store_id',$orderr->store_id)->value('person_id');
        $person_c=Customer::where('id',$orderr->customer_id)->value('persone_id');

        NotificationController::alertBayan("تم قبول الطلب",$person_s,$person_c,$store);

        return $this->sendResponse(200, 'successs');


    }

    //bayan
    public function delete_order($id)
    {
        $s = OrderStatus::where('status', '=', 'مرفوض')->value('id');
        $orderr=Order::where('id','=',$id)->first();
        $orderr->update(['status_id'=>$s]);
        $order = OrderProduct::where('order_id', '=', $id)->get();
        foreach ($order as $value) {
            $value->update(['status_id' => $s]);
        }

        $store=Store::where('id',$orderr->store_id)->value('name');
        $person_s=StoreManager::where('store_id',$orderr->store_id)->value('person_id');
        $person_c=Customer::where('id',$orderr->customer_id)->value('persone_id');

        NotificationController::alertBayan("تم رفض الطلب",$person_s,$person_c,$store);
        return $this->sendResponse(200, 'successs');

    }

    //bayan
    public function deliver_order($id)
    {
        $s = OrderStatus::where('status', '=', 'مسلم')->value('id');
        $orderr=Order::where('id','=',$id)->first();
        $orderr->update(['status_id'=>$s]);

        $store=Store::where('id','=',$orderr->store_id)->first();
        $n=0;
        $order = OrderProduct::where('order_id', '=', $id)->get();
        foreach ($order as $value) {
            $value->update(['status_id' => $s]);
            $n+=1;
        }

        $v=$n+$store->num_of_salling;
        $store->update(['num_of_salling'=>$v]);

        $person_s=StoreManager::where('store_id',$orderr->store_id)->value('person_id');
        $person_c=Customer::where('id',$orderr->customer_id)->value('persone_id');

        NotificationController::alertBayan("تم تسليم الطلب",$person_s,$person_c,$store->name);
    }


}
