<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderProductRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'product_id'=> 'required |exists:products,id',
            'order_id'=> 'required|exists:orders,id' ,
            'amount'=> 'required' ,
            'gift_order'=> 'required' ,
        ];
    }
}
