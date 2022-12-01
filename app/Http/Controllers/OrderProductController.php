<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\ResourcesBayan\mybill_resorce;
use App\Http\ResourcesBayan\ordure_product_resource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Http\Requests\StoreOrderProductRequest;
use App\Http\Resources\BoshraRe\BillResource;
use App\Http\Resources\BoshraRe\OrderProductResource;
use App\Models\OrderStatus;
use App\Models\Persone;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\StoreManager;
use Illuminate\Support\Facades\DB;

class OrderProductController extends BaseController
{

    ///انشاء الطلبات المعلقة
    ///boshra
    public function store(StoreOrderProductRequest $request)
    {

        $orderProduct = OrderProduct::Create([
            'product_id' => $request->product_id,
            'status_id' => OrderStatus::where('status', 'معلق')->value('id'),
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            "gift_order" => $request->gift_order,
            "discount_products_id" => Product::where('id', $request->product_id)->value('discount_products_id')
        ]);

        if ($orderProduct) {
            return $this->sendResponse(OrderProductResource::collection([$orderProduct]), "success");
        }
        return $this->sendErrors([], "error");
    }



    //////حذف المنتج المعلقs
    ///boshra
    public function delete_wating_order($order_product_id)
    {
        $res = OrderProduct::where('id', $order_product_id)->delete();
        if ($res)
            return $this->sendResponse($res, "success");
        else
            return $this->sendErrors([], "failed");
    }




    //boshra
    public function bill($order_id)
    {

        $data = DB::table('order_products')->where('order_products.order_id', $order_id)
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->get();


        return $this->sendResponse(BillResource::collection($data), 'success');
    }



    //bayan
    public function mybill($order_id, $store_maneger_id)
    {

        $data = DB::table('order_products')->where('order_products.order_id', $order_id)
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->get();

        $stor_maneger = StoreManager::where('id', '=', $store_maneger_id)->value('person_id');
        $email = Persone::where('id', '=', $stor_maneger)->value('email');


        return ["email" => $email, "data" => mybill_resorce::collection($data)];
    }


    //bayan
    public function order_product($id)
    {
        $product = OrderProduct::where('order_id', '=', $id)->get();
        $g = ordure_product_resource::collection($product);

        return $this->sendResponse($g, 'Store Shop successfully');
    }


    //boshra
    public function all_orderproduct($order_id,)
    {
        $data = OrderProduct::where('order_id', $order_id)->get();
        return $this->sendResponse(OrderProductResource::collection($data), 'success');
    }

    ///boshra
    public function edit_order_product(StoreOrderProductRequest $request)
    {
        $order_product = OrderProduct::where('order_id', $request->order_id)->where('product_id', $request->product_id)->update($request->all());

        $changes = OrderProduct::get()->sortByDesc('updated_at')->first();
        if ($order_product) {
            return $changes['id'];
        }
        return $this->sendErrors([], 'error');
    }
}
