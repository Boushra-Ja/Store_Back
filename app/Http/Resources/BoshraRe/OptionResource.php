<?php

namespace App\Http\Resources\BoshraRe;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{

    public function toArray($request)
    {
        return  [
            'product_options_id' => null,
            'value_id' => $this->id ,
            'name' => $this->name ,
            'value' => $this->value ,
            'option_type_id' => $this->option_type_id ,
        ];
    }
}
