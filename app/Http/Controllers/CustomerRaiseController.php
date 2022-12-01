<?php

namespace App\Http\Controllers;

use App\Events\MazadEvent;
use App\Events\NotificationEvent;
use App\Models\CustomerRaise;
use App\Http\Requests\StoreCustomerRaiseRequest;
use App\Http\Requests\UpdateCustomerRaiseRequest;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\Persone;
use App\Models\Product;
use App\Models\Raise;
use App\Models\StoreManager;
use Illuminate\Support\Facades\Auth;

class CustomerRaiseController extends Controller
{
    public function StartRaise($raise_id,$number)
    {

        $raisecustomer = CustomerRaise::create([
            'raise_id' =>$raise_id,
            'customer_id' => Customer::where('persone_id' ,'=', Auth::id())->value('id')
        ]);
        $raisecustomer->save();
        $name=Persone::where('id' ,'=', Auth::id())->value('name');
        broadcast(new MazadEvent($raise_id,Customer::where('persone_id' ,'=', Auth::id())->value('id'),$number,$name ));




    }

    public function EndRaise($raise_id,$price)
    {


        $raise= Raise::find($raise_id);
        if($raise) {
            $raise->status=1;
            $raise->price=$price;
            $raise->save();
        }
        $collection_id= Product::where('id', '=', $raise->product_id)->value("collection_id");
        $store_id=Collection::query()->where('id','=',$collection_id)->value('store_id');

        $storeManager = StoreManager::where('store_id', '=', $store_id)->value('person_id');

        NotificationController::alertBayan("انتهى المزاد",Auth::user()->id,$storeManager,$price.  "بالسعر التالي ");

        // broadcast(new NotificationEvent("انتهى المزاد",3,$price.  "بالسعر التالي " ));


    }
}
