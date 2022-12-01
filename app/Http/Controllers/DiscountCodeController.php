<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\ResourcesBayan\customerResource;
use App\Http\ResourcesBayan\discountCodeResource;
use App\Models\Customer;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Persone;
use Illuminate\Http\Request;

class DiscountCodeController extends BaseController
{

    public static function store(Request $request, $id, $stor_id, $h)
    {

        if ($h == 1) {

            $discount = DiscountCode::create([
                'discount_code' => ".",
                'discounts_id' => $id,
                'condition' => ".",
                'condition_value' => 0,
            ]);

        }
        else {
            $request->validate([
                'discount_code' => 'required',
                'condition' => 'required',
                'condition_value' => 'required',
            ]);

            //product  1
            //price    2
            $discount = DiscountCode::create([
                'discount_code' => $request->discount_code,
                'discounts_id' => $id,
                'condition' => $request->condition,
                'condition_value' => $request->condition_value,
            ]);

            $order = Order::where('store_id', '=', $stor_id)->get();

            $d = $order->groupBy('customer_id');

            if ($request->condition == 2) {
                $i = 0;
                $cust=array();
                foreach ($d as $value) {
                    foreach ($value as $v) {
                        $my_customer = Customer::where('id', '=', $v->customer_id)->first();
                        $sum = Order::where('customer_id', '=', $v->customer_id)->sum('delivery_price');
                        $customer_id = $v->customer_id;
                        break;
                    }
                    $orders = count($value);
                    $person = Persone::where('id', '=', $my_customer->persone_id)->first();

                    $cust[$i] = [
                        'orders' => $orders,
                        'name' => $person->name,
                        'date' => $person->created_at->format('Y-m-d '),
                        'total' => $sum,
                        'customer_id' => $customer_id
                    ];
                    $i += 1;
                }


                $i = 0;
                $customers=array();
                foreach ($cust as $value) {
                    if ($value["total"] >= $request->condition_value) {
                        $customers[$i] = $value["customer_id"];
                        $i += 1;

                    }

                }

                foreach ($customers as $c) {
                    DiscountCustomerController::store($discount->id, $c);
                }
            }
            else {

                $i = 0;
                $c = 0;
                $a=array();
                foreach ($d as $value) {
                    foreach ($value as $v) {
                        $my_customer = Customer::where('id', '=', $v->customer_id)->first();
                        $c += OrderProduct::where('order_id', '=', $v->id)->count();
                    }
                    $person = Persone::where('id', '=', $my_customer->persone_id)->first();
                    $a[$i] = [
                        'count' => $c,
                        'my_customer' => $my_customer,
                        'name' => $person->name,
                        'date' => $person->created_at->format('Y-m-d '),


                    ];
                    $i += 1;

                }


                $i = 0;
                $customers=array();
                foreach ($a as $item) {
                    if ($item["count"] >= $request->condition_value) {
                        $customers[$i] = $item["my_customer"]->id;
                        $i += 1;
                    }
                }


                foreach ($customers as $vv) {
                    DiscountCustomerController::store($discount->id, $vv);
                }
            }

        }

    }


    public static function store2($request, $id, $stor_id)
    {


            //product  1
            //price    2
            $discount = DiscountCode::create([
                'discount_code' => $request->discount_code,
                'discounts_id' => $id,
                'condition' => $request->condition,
                'condition_value' => $request->condition_value,
            ]);

            $order = Order::where('store_id', '=', $stor_id)->get();

            $d = $order->groupBy('customer_id');

            if ($request->condition == 2) {
                $i = 0;
                $cust=array();
                foreach ($d as $value) {
                    foreach ($value as $v) {
                        $my_customer = Customer::where('id', '=', $v->customer_id)->first();
                        $sum = Order::where('customer_id', '=', $v->customer_id)->sum('delivery_price');
                        $customer_id = $v->customer_id;
                        break;
                    }
                    $orders = count($value);
                    $person = Persone::where('id', '=', $my_customer->persone_id)->first();

                    $cust[$i] = [
                        'orders' => $orders,
                        'name' => $person->name,
                        'date' => $person->created_at->format('Y-m-d '),
                        'total' => $sum,
                        'customer_id' => $customer_id
                    ];
                    $i += 1;
                }


                $i = 0;
                $customers=array();
                foreach ($cust as $value) {
                    if ($value["total"] >= $request->condition_value) {
                        $customers[$i] = $value["customer_id"];
                        $i += 1;

                    }

                }

                foreach ($customers as $c) {
                    DiscountCustomerController::store($discount->id, $c);
                }
            }
            else {

                $i = 0;
                $c = 0;
                $a=array();
                foreach ($d as $value) {
                    foreach ($value as $v) {
                        $my_customer = Customer::where('id', '=', $v->customer_id)->first();
                        $c += OrderProduct::where('order_id', '=', $v->id)->count();
                    }
                    $person = Persone::where('id', '=', $my_customer->persone_id)->first();
                    $a[$i] = [
                        'count' => $c,
                        'my_customer' => $my_customer,
                        'name' => $person->name,
                        'date' => $person->created_at->format('Y-m-d '),


                    ];
                    $i += 1;

                }


                $i = 0;
                $customers=array();
                foreach ($a as $item) {
                    if ($item["count"] >= $request->condition_value) {
                        $customers[$i] = $item["my_customer"]->id;
                        $i += 1;
                    }
                }


                foreach ($customers as $vv) {
                    DiscountCustomerController::store($discount->id, $vv);
                }
            }



    }



}
