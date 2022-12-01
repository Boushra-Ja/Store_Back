<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\OptionType;
use App\Http\Requests\StoreOptionTypeRequest;
use App\Http\Requests\UpdateOptionTypeRequest;
use App\Http\Resources\BoshraRe\OptionResource;
use App\Models\OptioinValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionTypeController extends BaseController
{
    // اضافة خيار اضافي
    public function stor1(Request $request){
        foreach ($request->type as $vv) {

            OptionTypeController::store($vv, 3, 0);

        }

    }

    ///boshra
    public function option_product($product_id)
    {
        $options = OptionType::where('product_id' , $product_id)->get() ;

        $values = array() ;
        $i = 0   ;
        $j = 0 ;
        $res = array() ;

        foreach ($options as $value) {
            //$values[$i] = OptioinValue::where('option_type_id' , $value['id'])->get() ;

            $values[$i]  = DB::table('option_types')
            ->join('optioin_values', 'optioin_values.option_type_id', '=', 'option_types.id')
            ->where('option_types.id' , $value['id'])
            ->get() ;

            foreach ($values[$i] as   $val) {
                $res[$j] = $val ;
                $j = $j + 1;


            }
            $i = $i + 1 ;

        }
        return $this->sendResponse(OptionResource::collection($res) , "successs") ;
    }


    //bayan
    public static function store($type, int $product_id, int $i)
    {
        foreach ($type as $option) {
            if ($i == 0) {
                $optionType = OptionType::create([
                    'name' => $option,
                    'product_id' => $product_id
                ]);
                $i = $i + 1;
            } else if ($i == 1) {
                foreach ($option as $value) {
                    OptioinValueController::store($value, $optionType->id);
                }
            }


        }

    }
    //  تعديل الخيارات الاضافية
    //bayan
    public static function update($type, int $product_id)
    {

        $optionType = OptionType::where('product_id', '=', $product_id)->get();

        foreach ($optionType as $option) {
            $option->delete();
        }
        OptionTypeController::store($type, $product_id,0);

    }

}
