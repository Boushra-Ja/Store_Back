<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Collection;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\SecondrayClassification;
use App\Models\SecondrayClassificationProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class AllClassifficationResource extends JsonResource
{

    public function toArray($request)
    {

        $secondary = SecondrayClassification::where('classification_id', $this->id)->select('title', 'id')->get();

        $sum = 0;
        $i = 0;
        $sec_products = array();
        $res = array();
        $stores = array();
        $j = 0;
        $num_sales = 0 ;
        foreach ($secondary as $value) {

            $prod = SecondrayClassificationProduct::where('secondary_id' , $value['id'])->get() ;
            foreach ($prod as $key => $v) {
                $num_sales = $num_sales + OrderProduct::where('product_id' , $v['product_id'])
                ->where('status_id' , OrderStatus::where('status' , 'مسلم')->value('id'))
                ->count() ;
            }

            $sum = $sum +  SecondrayClassificationProduct::where('secondary_id', $value['id'])->count();

            $sec_products[$i] =  SecondrayClassificationProduct::where('secondary_id', $value['id'])->select('product_id')->get();
            foreach ($sec_products[$i] as $val) {
                $res[$j] = $val;
                $j++;
            }
            $i++;
        }

        $products = array();
        $i = 0;
        foreach ($res as $value) {

            $temp = Product::where('id', $value['product_id'])->value('collection_id');
            if (!in_array($temp, $products)) {
                $products[$i] = $temp;
            }
            $i++;
        }

        $sum_store = 0;
        $k = 0;
        foreach ($products as  $value) {

            $coll = Collection::Where('id', $value)->get();
            foreach ($coll as $val) {
                if (!in_array($val['store_id'], $stores)) {
                    $stores[$k] = $val['store_id'];
                    $sum_store = $sum_store + 1 ;
                    $k++;
                }
            }

        }

        return [
            'classification_id' => $this->id,
            'classification_title' => $this->title,
            'secondary_classiffication' => $secondary,
            'num_products' => $sum,
            'num_stores' => $sum_store ,
            'num_sales' => $num_sales
        ];
    }
}
