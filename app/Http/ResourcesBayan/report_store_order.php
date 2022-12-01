<?php

namespace App\Http\ResourcesBayan;

use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\Persone;
use Illuminate\Http\Resources\Json\JsonResource;

class report_store_order extends JsonResource
{


    public function toArray($request)
    {

        $customer = Customer::where('id', '=', $this->customer_id)->value('persone_id');
        $person = Persone::where('id', '=', $customer)->value('name');


        return [
            'name' => $person,
            'delivery_time' => $this->delivery_time,
            'delivery_price' => $this->delivery_price,
            'created_at' => $this->created_at,

        ];
    }
}
