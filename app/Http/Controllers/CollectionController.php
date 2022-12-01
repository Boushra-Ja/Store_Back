<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\BoshraRe\ProductResource;
use App\Http\ResourcesBayan\collection_product;
use App\Http\ResourcesBayan\dashbord_resours;
use App\Http\ResourcesBayan\product_classification;
use App\Http\ResourcesBayan\report_store_order;
use App\Http\ResourcesBayan\report_store_rate_product;
use App\Http\ResourcesBayan\report_store_rate_store;
use App\Http\ResourcesBayan\report_store_selles;
use App\Http\ResourcesBayan\report_storeSelles;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\RatingStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CollectionController extends BaseController
{

    //عرض منتجات متجر محدد
    //bayan
    public static function index($id)
    {


//        $product = DB::table('products')
//            ->join('collections', function ($join) use ($id) {
//                $join->on('collections.id', '=', 'products.collection_id')
//                    ->where('collections.store_id', '=', $id);
//            })
//            ->get();

        $colection = Collection::where('store_id', '=', $id)->get();


        $i = 0;
        $g2 = array();
        foreach ($colection as $value) {
            $product = Product::where('collection_id', '=', $value->id)->get();

            $g = product_classification::collection($product);
            foreach ($g as $v) {
                $g2[$i] = $v;
                $i = $i + 1;

            }
        }


        return $g2;


    }

    //عرض منتجات مجموعة محددة
    //bayan
    public function index2($id)
    {


        $product = Product::where('collection_id', '=', $id)->get();


        $g = product_classification::collection($product);

        return response()->json($g, 200);


    }

    //عرض مجموعات متجر محدد
    //bayan
    public function collectionNane(int $id)
    {
        $collection = Collection::where('store_id', '=', $id)->get();
        if ($collection) {

            $g = collection_product::collection($collection);

            return response()->json($g, 200);

        } else {
            return $this->sendErrors('failed in Store Shop', ['error' => 'not Store Shop']);

        }

    }

    // اضافة مجموعة
    //bayan
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'discription' => 'nullable',
            'image' => 'nullable',
            'store_id' => 'required',
        ]);





        $collection = Collection::create([
            'title'=>$request->title,
            'discription'=>$request->discription,
            'store_id'=>$request->store_id,
        ]);

        if($request->image)
        $collection->update(['image'=>$this->storeImage($request->image, $request->title),]);

        if ($collection) {
            return $this->sendResponse($collection, 'Store Shop successfully');

        } else {
            return $this->sendErrors('failed in Store Shop', ['error' => 'not Store Shop']);

        }

    }

    // تعديل مجموعة
    //bayan
    public function update(Request $request)
    {


        $collection = Collection::where('id', '=', $request->id)->first()->update([
            'title'=>$request->title,
            'discription'=>$request->discription,
            'image'=>$this->storeImage($request->image, $request->title),
            'store_id'=>$request->store_id,
        ]);
        return $this->sendResponse($collection, 'تم تعديل المجموعة بنجاح');
    }

    ///صورة
    /// bayan
    public function  storeImage($image, $title)
    {
        $newImageName = uniqid() . '-' . $title . '.' . $image->extension();
        $image->move(public_path('bayanImages'), $newImageName);
        return $newImageName;
    }

    //حذف مجموعة
    //bayan
    public function delete(Request $request)
    {
        $collection = Collection::where('id', '=', $request->id)->first()->delete();
    }

    //مجموعة محددة
    //bayan
    public function show($id)
    {
        $collection = Collection::where('id', '=', $id)->first();
        if ($collection)
            return response()->json($collection, 200);
        else
            return $this->sendErrors('failed in Collection', ['error' => 'not Collection']);


    }

    //bayan
    public function dashbord($id)
    {

        return dashbord_resours::make($id);
    }

    //bayan
    public function selles($id)
    {

        $collection = Collection::where('store_id', '=', $id)->get();

        $product = array();
        $i = 0;
        foreach ($collection as $value) {

            $p = Product::where('collection_id', '=', $value->id)->get();
            foreach ($p as $item) {
                $product[$i] = $item;
                $i += 1;
            }

        }

        $g = report_store_selles::collection($product);
        return $g;


    }

    //bayan
    public function orders($id)
    {

        $order = Order::where('store_id', '=', $id)->get();
        $g = report_store_order::collection($order);
        return $g;
    }

    //bayan
    public function rate_store($id)
    {

        $rate = RatingStore::where('store_id', '=', $id)->get();
        $g = report_store_rate_store::collection($rate);
        return $g;
    }

    //bayan
    public function rate_product($id)
    {

        $collection = Collection::where('store_id', '=', $id)->get();

        $product = array();
        $i = 0;
        foreach ($collection as $value) {

            $p = Product::where('collection_id', '=', $value->id)->get();
            foreach ($p as $item) {
                $pr = ProductRating::where('product_id', '=', $item->id)->get();
                foreach ($pr as $r) {
                    $product[$i] = $r;
                    $i += 1;

                }
            }

        }

        $g = report_store_rate_product::collection($product);
        return $g;
    }


    public function send_store($id)
    {

        $collection= Product::where('id', '=', $id)->value("collection_id");
        $store=Collection::query()->where('id','=',$collection)->value('store_id');



        return $store;
    }
}


