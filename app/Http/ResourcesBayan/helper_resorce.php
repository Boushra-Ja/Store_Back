<?php

namespace App\Http\ResourcesBayan;

use App\Models\Privilladge;
use App\Models\PrivilladgeHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class helper_resorce extends JsonResource
{

    public function toArray($request)
    {

        $a = array();
        $i = 0;

        $privillage_id =PrivilladgeHelper::where('helper_id','=',$this->id)->get();

        foreach ($privillage_id as $v) {
            $privillage_name = Privilladge::where('id', '=', $v->privilladge_id)->first();

            $a[$i] = $privillage_name->name;
            $i++;

        }

        return [
            'name' => $this->name,
            'email' => $this->email,
            'privillage' => $a,

        ];
    }

}
