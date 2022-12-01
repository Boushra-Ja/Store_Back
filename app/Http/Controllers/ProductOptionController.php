<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\ProductOption;
use App\Http\Requests\StoreProductOptionRequest;
use App\Http\Requests\UpdateProductOptionRequest;
use App\Http\Resources\BoshraRe\OptionResource;
use App\Models\OptioinValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ProductOptionController extends BaseController
{

    public function index()
    {
        //
    }



    /////اضافة خيارات المنتج
    ///boshra
    public function store(StoreProductOptionRequest $request)
    {

        $productOption = ProductOption::Create([
            'order_product_id' => $request->order_product_id,
            'option_values_id' => $request->option_values_id,
        ]);

        if ($productOption) {
            return $this->sendResponse($productOption, "success");
        }
        return $this->sendErrors([], "error");
    }


    public function show(ProductOption $productOption)
    {
        //
    }




    public function update_choice(UpdateProductOptionRequest $request , $id  )
    {
        $productOption = ProductOption::where('id' , $id)->update($request->all());
        return $this->sendResponse($productOption, 'تم تعديل ملف المتجر بنجاح');
    }


    ////////جلب الخيارات التي اختارها المستخدم للمنتج
    public function get_options($order_product_id)
    {

        $data = ProductOption::select('option_values_id' )->where('order_product_id', $order_product_id)->get();

        $values = array();
        $res = array();
        $types = array() ;
        $all_data = array () ;
        $i = 0;
        $k = 0 ;
        $j = 0;
        foreach ($data as $value) {
            $values[$i] = OptioinValue::where('id', $value['option_values_id'])->get();

            foreach ($values[$i] as $val) {
                $res[$j] = $val;
                $types[$j] = DB::table('optioin_values')->where('optioin_values.id' , $val['id'])
                ->join('option_types', 'optioin_values.option_type_id', '=', 'option_types.id')
                ->join('product_options' , 'product_options.option_values_id' , '=' , 'optioin_values.id')->where('product_options.order_product_id' , $order_product_id)
                ->select('product_options.id as product_options_id' ,'option_types.id as option_type_id' , 'optioin_values.id as value_id' , 'optioin_values.value as value' , 'option_types.name as name')
                ->get();
                foreach ($types[$j] as $v) {
                    $all_data[$k] = $v ;
                    $k++ ;
                }
                $j++;
            }
            $i++;
        }

        if($all_data)
            return  $this->sendResponse($all_data, "successs");

        return  $this->sendErrors([], "failed");

    }

    ///boshra
    public function edit_option_product(UpdateProductOptionRequest $request , $id)
    {
        $order_product = ProductOption::where('id', $id)->update($request->all());
        if ($order_product) {
            return $this->sendResponse($order_product, 'success');
        }
        return $this->sendErrors([], 'error');
    }
}
