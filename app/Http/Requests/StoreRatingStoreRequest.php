<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'value' => 'required | max:5'  ,
            'customer_id' => 'required|exists:customers,persone_id',
            'store_id' => 'required | exists:stores,id'
        ];
    }
}
