<?php

namespace App\Http\ResourcesBayan;

use App\Models\Collection;
use App\Models\DiscountProduct;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class discount_resource extends JsonResource
{

    public function toArray($request)
    {

        $products = array();
        $i = 0;
       $product = DiscountProduct::where('discounts_id', '=', $this->id)->first();



//        $product = DiscountProduct::where('discounts_id', '=', $this->id)->whereDate($this->start_date, '<=', date('Y-m-d'))->whereDate($this->end_date, '>=', date('Y-m-d'))->first();
//        if ($product)
//            $state = "1";
//        else {
//            $state = "0";
//            $product = DiscountProduct::where('discounts_id', '=', $this->id)->first();
//
//        }


        if ($product->apply_to == 'p') {
            $p = Product::where('discount_products_id', '=', $product->id)->get();
            foreach ($p as $v) {
                $products[$i] = $v->name;
                $i += 1;

            }
        } else if ($product->apply_to == 'c') {
            $p = Product::where('discount_products_id', '=', $product->id)->get();
            $d = $p->groupBy('collection_id');
            foreach ($d as $value) {
                foreach ($value as $v) {
                    $cc = Collection::where('id', '=', $v->collection_id)->value('title');
                    $products[$i] = $cc;
                    $i += 1;
                    break;

                }
            }
        }


//
//        $mytime = Carbon::createFromFormat('m/d/Y H:i:s', date('Y-m-d H:i:s'));
//        $date1 = Carbon::createFromFormat('m/d/Y H:i:s', $this->start_date);
//        $date2 = Carbon::createFromFormat('m/d/Y H:i:s', $this->end_date);
//
//        $result1 = $mytime->gte($date1);
//        $result2 = $mytime->lte($date2);
//
//        if($result1 && $result2 )
//            $state="1";
//        else
//            $state="0";


        return [
            'discounts_id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'title' => $product->title,
            'apply_to' => $product->apply_to,
            'products' => $products,
        //    'state' => $state

        ];
    }
}
