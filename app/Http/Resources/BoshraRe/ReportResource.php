<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Customer;
use App\Models\Persone;
use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{

    public function toArray($request)
    {
        return [

            'report_id' => $this->id ,
            'content_report' => $this->content,
            'store_id' => $this->store_id ,
            'store_name' => Store::where('id' , $this->store_id )->value('name'),
            'customer_id' => $this->customer_id ,
            'customer_name' => Persone::where('id'  , Customer::where('id' , $this->customer_id )->value('persone_id'))->value('name'),
            'created_at' => $this->created_at->format('Y-m-d '),

        ];
    }
}
