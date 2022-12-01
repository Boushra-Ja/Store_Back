<?php

namespace App\Http\ResourcesBayan;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Persone;
use Illuminate\Http\Resources\Json\JsonResource;

class discountCodeResource extends JsonResource
{

    public function toArray($request)
    {

        $c=0;
        foreach ($this as $value) {
            foreach ($value as $v) {
                $my_customer = Customer::where('id', '=', $v->customer_id)->first();
                $c+=OrderProduct::where('order_id', '=', $v->id)->count();
            }
            break;

        }

        $person = Persone::where('id', '=', $my_customer->persone_id)->first();
        return [
            'count' => $c,
            // 'my_customer' => $my_customer,
            'name'=>$person->name,
            'date'=>$person->created_at,


        ];
    }

}
