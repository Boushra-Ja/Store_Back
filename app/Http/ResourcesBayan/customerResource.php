<?php

namespace App\Http\ResourcesBayan;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Persone;
use Illuminate\Http\Resources\Json\JsonResource;

class customerResource extends JsonResource
{

    public function toArray($request)
    {


        foreach ($this as $value) {
            foreach ($value as $v) {
                $my_customer = Customer::where('id', '=', $v->customer_id)->first();
                $sum = Order::where('customer_id', '=', $v->customer_id)->sum('delivery_price');

                break;
            }

            $orders = count($value);
            break;
        }


        $person = Persone::where('id', '=', $my_customer->persone_id)->first();
        return [
            'orders' => $orders,
            'name' => $person->name,
            'email'=>$person->email,
            'date' => $person->created_at,
            'total' => $sum

        ];
    }
}
