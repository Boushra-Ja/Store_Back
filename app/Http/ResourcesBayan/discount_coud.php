<?php

namespace App\Http\ResourcesBayan;

use App\Models\DiscountCode;
use App\Models\DiscountProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class discount_coud extends JsonResource
{

    public function toArray($request)
    {

        $product = DiscountCode::where('discounts_id', '=', $this->id)->first();
        return [
           // 'id' => $product->id,
            'discounts_id'=>$this->id,
            'type' => $this->type,
            'value' => $this->value,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'discount_code' => $product->discount_code,
            'condition' => $product->condition,
            'condition_value' => $product->condition_value
        ];
    }
}
