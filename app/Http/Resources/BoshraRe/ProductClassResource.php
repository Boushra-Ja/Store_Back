<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Collection;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductClassResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'product_id' => $this->product_id ,
            'product_name' => $this->name ,
            'image' => $this->image ,
            'selling_price' => $this->selling_price,
            'collection_id' => $this->collection_id,
            'classification_id'=>$this->classification_id,
            'secondary_id' => $this->secondary_id,
            'secondray_title' => $this->secondray_title,
            'classifications_title' => $this->classifications_title,
            'class_product_id'=>$this->id,
        ];
    }
}
