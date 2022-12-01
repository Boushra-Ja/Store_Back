<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Product;
use App\Models\SecondrayClassification;
use App\Models\SecondrayClassificationProduct;
use Illuminate\Http\Request;

class SecondrayClassificationProductController extends BaseController
{

    //bayan
    public static function store(int $product_id, int $secondrayClassification_id)
    {
        $secondrayClassification = SecondrayClassification::find($secondrayClassification_id);
        $product = Product::find($product_id);

        $response = $secondrayClassification->product()->attach($product);

        return response()->json($response, 200);
    }

    //bayan
    public static function update(int $product_id, int $secondrayClassification_id)
    {


        SecondrayClassificationProductController::store($product_id, $secondrayClassification_id);

    }
    public static function show($product_id)
    {

        $secondrayClassification = SecondrayClassificationProduct::where('product_id', '=', $product_id->id)->get();
        $data = SecondrayClassificationController::show_product($secondrayClassification);

        return response()->json($data, 200);


    }

}
