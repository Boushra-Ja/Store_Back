<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{

    public function toArray($request)
    {
        return  [
            'discount_codes_id' => $this->id,
            'start_date' => $this->start_date ,
            'end_date' => $this->end_date ,
            'discount_code' => $this->discount_code ,
            'discounts_id' => $this->discounts_id ,
            'value' => $this->value ,
            'store_id' =>$this->store_id ,
            'store_name' => Store::where('id' , $this->store_id )->value('name'),
            'discount_customer_id' => $this->discount_customers_id
        ];
    }
}
