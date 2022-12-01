<?php

namespace App\Http\Controllers;

use App\Events\MazadEvent;
use App\Events\NotificationEvent;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\CustomerRaise;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Persone;
use App\Models\Privilladge;
use App\Models\Product;
use App\Models\Raise;
use App\Models\StoreManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaiseController extends Controller
{
    public function store(Request $request)
    {

        $raise = Raise::create([
            'price' => $request->price,
            'date' => $request->date,
            'status' => "0",
            'product_id' => $request->product_id,
        ]);
        $name = Product::where('id', '=', $request->product_id)->value('name');
        $order_product = OrderProduct::where('product_id', '=', $request->product_id)->get();

        foreach ($order_product as $item) {
            $order = Order::where('id', '=', $item->order_id)->value('customer_id');
            $customer = Customer::where('id', '=', $order)->first();
            $response = $raise->customer()->attach($customer);
            NotificationController::alertBayan("$raise->id",
                $request->id,
                $customer->persone_id,
                "مزاد",);
        }

    }

    public function has_A_raise($product_id)
    {

        $raise = Raise::where('product_id', '=', $product_id)->first();

        if ($raise) {
            return response()->json([
                'data' => "1",
                "date" => $raise->date,
            ], 200);
        } else
            return response()->json([
                'data' => "0",
            ], 200);

    }



}
