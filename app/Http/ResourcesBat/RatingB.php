<?php

namespace App\Http\ResourcesBat;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingB extends JsonResource
{

    public function toArray($request)
    {
        return  [
            'value' => $this->value,


        ];
    }
}
