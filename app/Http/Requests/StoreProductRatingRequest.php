<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRatingRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'value' => 'required' ,
            'customer_id' => 'required |exists:customers,persone_id',
            'product_id' => 'required |exists:products,id',

        ];
    }
}
