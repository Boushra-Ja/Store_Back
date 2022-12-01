<?php

namespace App\Http\ResourcesBayan;

use App\Models\Persone;
use App\Models\Store;
use App\Models\StoreManager;
use Illuminate\Http\Resources\Json\JsonResource;

class waitStore extends JsonResource
{

    public function toArray($request)
    {


        $store=Store::where('id', '=', $this->store_id)->first();
        $person=Persone::where('id', '=', StoreManager::where('store_id', '=', $this->store_id)->value('person_id'))->first();

        return  [
            'person_name' => $person->name ,
            'person_email' => $person->email ,
            'store' => $store ,
        ];
    }

}
