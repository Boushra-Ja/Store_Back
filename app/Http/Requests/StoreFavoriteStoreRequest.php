<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFavoriteStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'store_id' =>'required|exists:stores,id',
            'customer_id'=>'required|exists:customers,persone_id'
        ];
    }
}
