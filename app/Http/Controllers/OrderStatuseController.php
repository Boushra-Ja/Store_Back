<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\StoreOrderStatuseRequest;
use App\Models\OrderStatus;

class OrderStatuseController extends BaseController
{


    public function index()
    {
        $status = OrderStatus::all() ;
        return $this->sendResponse($status , "success");
    }


    ///boshra
    public function store(StoreOrderStatuseRequest $request)
    {
        $order_status = OrderStatus::create($request->all()) ;

        if($order_status)
        {
            return $this->sendResponse($order_status, "تم اضافة حالة طلب جديدة") ;
        }
        return $this->sendErrors("مشكلة في اضافة حالة جديدة للطلب ") ;
    }

}
