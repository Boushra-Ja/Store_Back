<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Classification;
use App\Http\Requests\StoreClassificationRequest;
use App\Http\Requests\UpdateClassificationRequest;
use App\Http\Resources\BoshraRe\AllClassifficationResource;
use App\Models\Product;
use App\Models\SecondrayClassification;
use App\Models\SecondrayClassificationProduct;
use Illuminate\Http\Request;

class ClassificationController extends BaseController
{


    //عرض التصنيفات
    //bayan
    public function Show_Classification()
    {
        $ClassificationModel = Classification::query()->get();
        $a = array();
        $i = 0;
        foreach ($ClassificationModel as $value) {
            $secundery = SecondrayClassification::where('classification_id', '=', $value->id)->get();
            $c = 0;

            foreach ($secundery as $t) {
                $product = SecondrayClassificationProduct::where('secondary_id', '=', $t)->get();
                $c += count($product);
            }
            $a[$i] = ["classification" => $value->title, "id" => $value->id, "secondrayClassification" => $secundery, "product" => $c];
            $i+=1;
        }
        return response()->json($a, 200);


    }

    // اضافة تصنيف
    //bayan
    public static function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
        ]);
        $classification = Classification::create([
            'title' => $request->title
        ]);


        if ($classification) {
            foreach ($request->secondray as $value) {
                SecondrayClassificationController::store($value, $classification->id);
            }
        }
    }

    //bayan
    public function show(int $id){
        $classification=Classification::where('id','=',$id)->first();
        $secondery=SecondrayClassification::where('classification_id','=',$classification->id)->get();
        return response()->json(['classification'=>$classification->title,'secondery'=>$secondery], 200);
    }

    ///boshra
    public function All_classifications()
    {

        $classification = Classification::select('id' , 'title')->get() ;
        return $this->sendResponse(AllClassifficationResource::collection($classification) , 'success') ;
    }

}
