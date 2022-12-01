<?php


namespace App\Http\ResourcesBat;

use App\Models\SecondrayClassification;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassificationB extends JsonResource
{

    public function toArray($request)
    {
        return  [
             'id' =>$this->id,
            'title' => $this->title ,
            'secendary' => ClassificationSecB::collection(SecondrayClassification::where('classification_id', $this->id)->get()),


        ];
    }
}
