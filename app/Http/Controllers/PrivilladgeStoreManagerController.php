<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Privilladge;
use App\Models\StoreManager;

class PrivilladgeStoreManagerController extends BaseController
{

    //bayan
    public Static function store($privilladge_id,$storeManager_id)
    {
        $privilladge=Privilladge::where('id','=',$privilladge_id)->first();
        $storeManager=StoreManager::where('id','=',$storeManager_id)->first();

        $response=$privilladge->storeManager()->attach($storeManager);
        return response()->json($response,200);
    }
}
