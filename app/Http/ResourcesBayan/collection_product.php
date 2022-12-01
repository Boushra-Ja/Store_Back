<?php

namespace App\Http\ResourcesBayan;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class collection_product extends JsonResource
{

    public function toArray($request)
    {
        return  [
            'id'=>$this->id,
            'image' => $this->image,
            'title' => $this->title,
            'discription' => $this->discription,
            //  'classification'=> SecondrayClassification::where('id','=',SecondrayClassificationProduct::where('id','=',$this->id)->value('id'))->get()->value('title'),
            'number'=> Product::where('collection_id','=',$this->id)->count(),

        ];
    }



}
