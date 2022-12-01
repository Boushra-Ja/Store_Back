<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Customer;
use App\Models\Persone;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RatingResource extends JsonResource
{

    public function toArray($request)
    {
        return  [
            'rating_id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer_name' => Persone::where('id' , $this->customer_id)->value('name'),
            'value' => $this->value,
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),

        ];
    }
}
