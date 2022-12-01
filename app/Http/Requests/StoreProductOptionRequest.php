<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductOptionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'order_product_id' => 'required |exists:order_products,id' ,
            'option_values_id' => 'required|exists:optioin_values,id'
        ];
    }
}
