<?php

namespace App\Http\Controllers;

use App\Models\StoreVisitore;
use App\Http\Requests\StoreStoreVisitoreRequest;
use App\Http\Requests\UpdateStoreVisitoreRequest;

class StoreVisitoreController extends Controller
{

    ////boshra
    public function store(StoreStoreVisitoreRequest $request)
    {
        $visiter = StoreVisitore::where('store_id' , $request->store_id)->where('visit_date' , $request->visit_date);
        if($visiter->first())
        {
            $visiter->update([

                'visit_num' => $visiter->value('visit_num') + 1
            ]);
        }
        else{
            $visiter->create([

                'visit_num' =>  1 ,
                'store_id' =>  $request->store_id ,
                'visit_date' => $request->visit_date
            ]);
        }
    }

    //boshra
    public function index()
    {

    }

    ///boshra
    public function destroy(StoreVisitore $storeVisitore)
    {
        //
    }
}
