<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Privilladge;
use App\Models\Store;
use App\Models\StoreManager;
use Illuminate\Http\Request;

class HelperController extends Controller
{

    //bayan
    public Static function store(Request $request){


        $request->validate([
            'helper_name' => 'required',
            'helper_email' => 'required',
            'store_manager_id' => 'required',
        ]);

        $helper = Helper::create([
            'name'=>$request->helper_name,
            'email'=>$request->helper_email,
            'store_manager_id'=>$request->store_manager_id,
            'status'=>'0'
        ]);

        if($helper){
            $j=json_decode($request->privilladge);
            foreach($j as $value){
              //  $v=Privilladge::where('name','=',$value)->first();
                PrivilladgeHelperController::store($value, $helper->id);
            }

              mailcontrol::invetation($helper->name, $helper->email, 'دعوة لادارة متجر');

        }

    }

    //bayan
    //تسجيل الدخول كمساعد
    public function accept_help(Request $request){
        $helper=Helper::where('email','=',$request->email)->first();
        if($helper){
            $helper->update([
                'status'=>1
            ]);
            $shop=StoreManager::where('id','=',$helper->store_manager_id)->value('store_id');
            $id=StoreManagerController::register($request, $shop,2,"");
        }

    }

}
