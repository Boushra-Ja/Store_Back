<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\StoreFavoriteStoreRequest;
use App\Http\Resources\BoshraRe\StoresResource;
use App\Models\Customer;
use App\Models\FavoriteStore;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteStoreController extends BaseController
{

    //عرض مفضله المتاجر للزبون//
    public function Show_Favorite()
    {
        $favorite = DB::table('stores')
            ->join('favorite_stores', function ($join) {
                $join->on('stores.id', '=', 'favorite_stores.store_id')
                ->where('favorite_stores.customer_id', '=', Customer::where('persone_id' ,  optional(Auth::user())->id)->value('id'));

            })
            ->select( 'stores.*')->get();
        return $this->sendResponse(StoresResource::collection($favorite) , 'success');
    }
    public  function  index()
    {
        $store = Store::all();
        return StoresResource::Collection($store);
    }

    ////////ارجاع مفضلتي
    public function myFavorite($user_id)
    {

        $favorite = FavoriteStore::select('store_id')->where('customer_id', Customer::where('persone_id' , $user_id)->value('id'))->get();
        if ($favorite)
            return $this->sendResponse($favorite, 'Success');
        else
            return $this->sendErrors([], 'Failed');
    }
    //اضافه لمفضله المتاجر//
    public function Add_Favorite(StoreFavoriteStoreRequest $request)
    {

        $response = FavoriteStore::Create(
            [
                'store_id' => $request->store_id,
                'customer_id' => Customer::where('persone_id', $request->customer_id)->value('id')
            ]
        );

        if ($response)
            return $this->sendResponse($response, "success");

        return $this->sendErrors([], "failed");
    }


    //حدف مننج من مفضله المتاجر//
    public function Delete_Favorite($store_id, $cus_id)
    {
        $res = FavoriteStore::where('store_id', $store_id)->where('customer_id', Customer::where('persone_id', $cus_id)->value('id'))->delete();
        if ($res)
            return $this->sendResponse($res, "success");
        else
            return $this->sendErrors([], "failed");
    }

    //boshra
    public function isFavouriteStore($store_id, $customer_id)
    {

        $check = FavoriteStore::where('customer_id', Customer::where('persone_id' , $customer_id)->value('id'))->where('store_id', $store_id)->first();

        if ($check)
            return 1;
        else
            return 0;
    }
}
