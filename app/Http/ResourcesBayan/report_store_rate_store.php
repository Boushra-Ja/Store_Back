<?php

namespace App\Http\ResourcesBayan;

use App\Models\Customer;
use App\Models\Persone;
use Illuminate\Http\Resources\Json\JsonResource;

class report_store_rate_store extends JsonResource
{

    public function toArray($request)
    {

        $customer = Customer::where('id', '=', $this->customer_id)->value('persone_id');
        $person = Persone::where('id', '=', $customer)->value('name');

        return [
            'name' => $person,
            'notes' => $this->notes,
            'value' => $this->value,
            'created_at' => $this->created_at,

        ];
    }

}
