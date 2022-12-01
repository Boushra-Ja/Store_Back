<?php

namespace App\Http\ResourcesBayan;

use App\Http\Resources\BoshraRe\RatingResource;
use App\Models\Collection;
use App\Models\OptioinValue;
use App\Models\OptionType;
use App\Models\OrderProduct;
use App\Models\ProductRating;
use App\Models\SecondrayClassification;
use App\Models\SecondrayClassificationProduct;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class product_classification extends JsonResource
{

    public function toArray($request)
    {


        $i = 0;
        $s = SecondrayClassificationProduct::where('product_id', '=', $this->id)->get();
        $sec = array();
        foreach ($s as $value) {
            $sec[$i] = SecondrayClassification::where('id', '=', $value->secondary_id)->value('title');
            $i += 1;

        }

        $order=OrderProduct::where('product_id','=',$this->id)->get();
        $order_count=count($order);


        $rate = ProductRating::where('product_id', $this->id)->get();
        $i1 = 0;
        $i2 = 0;
        $i0 = 0;
        foreach ($rate as $item) {
            if ($item->value == 0)
                $i0 += 1;
            else if ($item->value == 1)
                $i1 += 1;
            else if ($item->value == 2)
                $i2 += 1;
        }

        if ($i1 == 0 && $i2 == 0 && $i0 == 0)
            $reselt = 3;
        else if ($i0 <= $i1) {
            if ($i1 <= $i2)
                $reselt = 2;
            else
                $reselt = 1;
        } else {
            if ($i0 <= $i2)
                $reselt = 2;
            else
                $reselt = 0;
        }

        $type_value=array();
        $j=0;

        $type=OptionType::where('product_id', $this->id)->get();
        foreach ($type as $item){
            $value=OptioinValue::where('option_type_id','=',$item->id)->get(['value']);
            $type_value[$j]=[$item->name,$value];
            $j+=1;
        }
        return [
            'product_id' => $this->id,
            'product_name' => $this->name,
            'image' => $this->image,
            'selling_price' => $this->selling_price,
            'num_cell' => $this->number_of_sales,
            'prepration_time' => $this->prepration_time,
            'party' => $this->party,
            'discription' => $this->discription,
            'age' => $this->age,
            'gift' => $this->gift,
            'cost_price' => $this->cost_price,
            'return_or_replace' => $this->return_or_replace,
            'discount_products_id' => $this->discount_products_id,
            'classification' => $sec,
            'collection' => Collection::where('id', '=', $this->collection_id)->value('title'),
            'review' => $reselt,
            'order_count'=>$order_count,
            'type_value'=>$type_value
        ];
    }
}
