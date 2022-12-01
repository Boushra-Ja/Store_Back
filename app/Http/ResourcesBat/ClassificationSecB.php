<?php

namespace App\Http\ResourcesBat;

use App\Models\Classification;
use App\Models\SecondrayClassification;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassificationSecB extends JsonResource
{

    public function toArray($request)
    {

        return  [
            'title' => $this->title,
        ];
    }
}
