<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\OptioinValue;
use App\Http\Requests\StoreOptioinValueRequest;
use App\Http\Requests\UpdateOptioinValueRequest;
use App\Models\OptionType;

class OptioinValueController extends BaseController
{
    ///boshra
    public static function options_type_with_value($type_id)
    {
        $values = OptioinValue::where('option_type_id' , $type_id)->get() ;
        return $values  ;

    }

    //bayan
    public Static function store( $value,int $option_type_id)
    {
        OptioinValue::create([
            'value' => $value,
            'option_type_id' => $option_type_id
        ]);

    }

    ///boshra
    public function All_material()
    {
        $types = OptionType::select('id')->where('name' , 'المادة')->get() ;
        $values = array () ;
        $res = array() ;
        $i = 0 ;
        $j = 0 ;
        foreach ($types as  $value) {

            $values[$i]  = OptioinValue::select('value' , 'id')->where('option_type_id' , $value['id'])->get() ;
            foreach ($values[$i]  as  $val) {
                $res[$j] = $val ;
                $j++ ;
            }
            $i++;
        }
        return $this->sendResponse($res , 'success') ;
    }

}
