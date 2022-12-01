<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'customer_id'=> 'required |exists:customers,persone_id',
            'store_id'=> 'required|exists:stores,id' ,
            'delivery_price' => 'required' ,
            'delivery_time' => 'required'
        ];
    }
}
