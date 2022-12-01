<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\BoshraRe\ProductAllResource;
use App\Models\Customer;
use App\Models\FavoriteProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteProductController extends BaseController
{
    //عرض مفضله المنتجات للزبون مع التقيمات //
    public function index()
    {


        $favorite = DB::table('products')
        ->join('favorite_products', function ($join) {
            $join->on('products.id', '=', 'favorite_products.product_id')
            ->where('favorite_products.customer_id', '=', Customer::where('persone_id' ,  optional(Auth::user())->id)->value('id'));

        })
        ->select( 'products.*')->get();
        if ($favorite) {
            return $this->sendResponse(ProductAllResource::collection($favorite) , 'success');
        } else {
            return "null";
        }
    }


    //اضافه لمفضله المنتجات او حذف  //
    public function store($id)
    {

        $v = "delete_favorite";
        $c = "add_to_favorite";
        $favorite = FavoriteProduct::where([
            'product_id' => $id,
            'customer_id' => Customer::where('persone_id' , optional(Auth::user())->id)->value('id'),
            ])->first();
        if (!is_null($favorite)) {
            $favorite->delete();
            return $v;
        } else {
            FavoriteProduct::create([
                'customer_id' => Customer::where('persone_id' , optional(Auth::user())->id)->value('id'),
                'product_id' => $id
            ]);

            return
                $c;
        }
    }


    public function show()
    {
        $e = FavoriteProduct::query()->where('customer_id','=', Customer::where('persone_id' , optional(Auth::user())->id)->value('id')   )
            ->get(['product_id', 'id']);
        return response()->json($e, 200);
    }


    //حدف مننج من مفضله المنتجات//
    public function destroy($id)
    {
        $FavoriteProductModel = FavoriteProduct::findOrFail($id);
        $FavoriteProductModel->delete();
    }


    //boshra
    public function isFavourite($product_id, $customer_id)
    {

        $check = FavoriteProduct::where('customer_id', Customer::where('persone_id' , $customer_id)->value('id'))->where('product_id', $product_id)->first();

        if ($check)
            return 1;
        else
            return 0;
    }
}
