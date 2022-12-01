<?php

namespace App\Http\Controllers;

use App\Http\ResourcesBayan\helper_resorce;
use App\Models\Helper;
use App\Models\Privilladge;

class PrivilladgeHelperController extends Controller
{

    //bayan
    public Static function store($privilladge_id,$helper_id)
    {
        $privilladge=Privilladge::find($privilladge_id);
        $helper=Helper::find($helper_id);

        $response=$privilladge->helper()->attach($helper);
        return response()->json($response,200);
    }


    //bayan

    public function my_helper($id){

        $helper=Helper::where('store_manager_id','=',$id)->get();

        return helper_resorce::collection($helper);

    }
}
