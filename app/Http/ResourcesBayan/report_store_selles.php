<?php

namespace App\Http\ResourcesBayan;


use App\Models\OrderProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class report_store_selles extends JsonResource
{

    public function toArray($request)
    {

        $order = OrderProduct::where('product_id', '=', $this->id)->get();
        $product_count = count($order);

        $profit = $product_count * ($this->selling_price - $this->cost_price);

        return [
            'name' => $this->name,
            'selling_price' => $this->selling_price,
            'selling_count' => $product_count,
            'Profit' => $profit,
            'cost_price'=>$this->cost_price
        ];
    }
}
