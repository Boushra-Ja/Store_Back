<?php

namespace App\Http\Controllers;

use App\Http\ResourcesBayan\store_show_resors;
use App\Models\Persone;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\BoshraRe\DashBoardResource;
use App\Http\Resources\BoshraRe\ProductClassResource;
use App\Http\Resources\BoshraRe\RatingProfileResource;
use App\Http\Resources\BoshraRe\StoreResource;
use App\Http\Resources\BoshraRe\StoreTasneemResource;
use App\Http\ResourcesBat\RatingProB;
use App\Models\Collection;

class StoreController extends BaseController
{

    ////عرض جميع المتاجر
    //boshra
    public function index()
    {
        $stores = Store::where('status' , 1)->get();
        return $this->sendResponse(StoreResource::collection($stores), "تمت عملية عرض المتاجر بنجاح");
    }


    /////shop names
    public function shop_names()
    {
        $stores = Store::select('name as value', 'id')->where('status' , 1)->get();
        return $this->sendResponse($stores, 'success');
    }

    ////عرض المنتجات الأكثر تقييماً
    //boshra
    public function order_by_review()
    {
        $stores = Store::where('status' , 1)->get() ;
        $list = StoreResource::collection($stores) ;
        $statisticCollection = collect($list);
        $sorted = $statisticCollection->sortByDesc('rate');

        return $this->sendResponse($sorted->values()->take(10)  , ' success');
    }

    ////عرض المنتجات الأكثر مبيعاً
    //boshra
    public function order_by_sales()
    {
        $data = Store::select("*")->where('status' , 1)->orderBy('num_of_salling', 'DESC')->get();
        return $this->sendResponse(StoreResource::collection($data), "تم ارجاع المتاجر حسب الاكثر مبيعاً");
    }

    /////انشاء متجر bayan
    public function store(StoreStoreRequest $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'discription' => ['required', 'string'],
            'delivery_area' => ['required', 'string'],
            'image',
            'Brand',
            'facebook',
            'mobile',
        ]);


        $shop = Store::create([
            'name' => $request->name,
            'delivery_area' => $request->delivery_area,
            'discription' => $request->discription,
            'num_of_salling' => "0",
            'facebook' => $request->facebook,
            'status' => "0"
        ]);

        if ($request->image){
            $image= $this->storeImage($request->image, $request->name);
            $shop->update([
                'image' => $image,
            ]);
        }


        if ($request->Brand)
            $shop->update([
                'Brand' => $this->storeImage($request->Brand, $request->name),
            ]);


        if ($shop) {

            WaitingStoreController::store($shop->id);


            DiscountController::store($request, $shop->id, 1);

           $manager_id=StoreManagerController::register($request, $shop->id,1,$image);


           return ['shop_id'=>$shop->id,'manager_id'=>$manager_id];
        }
    }

    ////عرض متجر محدد
    //boshra
    public function show($id)
    {
        $data = Store::where('id', $id)->get();
        if ($data) {
            return $this->sendResponse(StoreResource::collection($data), 'تم ارجاع معلومات المتجر بنجاح');
        } else {
            return $this->sendErrors('خطأ في عرض معلومات المتجر', ['error' => 'error in show product info']);
        }
    }


    ////عرض متجر محدد
    /// bayan
    public function myshow($id)
    {
        $data = Store::where('id', $id)->get();
        if ($data) {
            return $this->sendResponse(store_show_resors::collection($data), 'تم ارجاع معلومات المتجر بنجاح');
        } else {
            return $this->sendErrors('خطأ في عرض معلومات المتجر', ['error' => 'error in show store']);
        }
    }

    ////////تعديل بيانات المتجر
    /// bayan
    public function update(Request $request)
    {
        $persone = Persone::where('id', '=', $request->persone_id)->first();
        if ($persone)
            if ($persone->password == $request->old_password) {

                $store = Store::where('id', '=', $request->store_id)->first();
                $store->update([
                    'name' => $request->name,
                    'delivery_area' => $request->delivery_area,
                    'discription' => $request->discription,
                    'facebook' => $request->facebook,
                ]);
                if ($request->image)
                    $store->update([
                        'image' => $this->storeImage($request->image, $request->name),
                    ]);

                if ($request->Brand)
                    $store->update([
                        'Brand' => $this->storeImage($request->Brand, $request->name),
                    ]);
                StoreManagerController::update($request);
                return $this->sendResponse($store, 'تم تعديل ملف المتجر بنجاح');
            } else return $this->sendResponse("erorr", 'كلمة السر غير مطابقة');
    }

    ///صورة
    /// bayan
    public function storeImage($image, $title)
    {
        $newImageName = uniqid() . '-' . $title . '.' . $image->extension();
        $image->move(public_path('uploads\stores'), $newImageName);
        return $newImageName;
    }

    ///جلب المنتجات مع تصنيفاتها
    //boshra
    public function product_with_class($store_id)
    {
        $collections_id = Collection::where('store_id', $store_id)->get();

        $pr = array();
        $i = 0;
        $res = array();
        $j = 0;
        foreach ($collections_id as $value) {
            $pr[$i] = DB::table('products')->where('products.collection_id', $value['id'])
                ->join('secondray_classification_products', 'products.id', '=', 'secondray_classification_products.product_id')
                ->join('secondray_classifications', 'secondray_classification_products.secondary_id', '=', 'secondray_classifications.id')
                ->join('classifications', 'classifications.id', '=', 'secondray_classifications.classification_id')
                ->select('secondray_classifications.id as secondary_id', 'secondray_classifications.title as secondray_title', 'secondray_classifications.classification_id as classification_id', 'classifications.title as classifications_title', 'products.*', 'secondray_classification_products.*')
                ->get();

            foreach ($pr[$i] as $val) {


                $res[$j] = $val;
                $j++;


            }
            $i++;
        }
        return $this->sendResponse(ProductClassResource::collection($res), 'success');
    }

    /////boshra
    public function search_by_name($name)
    {
        $data = Store::query()
            ->where('name', 'LIKE', '%' . $name . '%')
            ->where('status' , 1)
            ->get();
        if ($name == "")
            return $this->sendResponse(StoreResource::collection(Store::all()), 'success');


        return $this->sendResponse(StoreResource::collection($data), 'success');
    }

    ///boshra
    public function all_stores()
    {

        $stores = Store::where('status' , 1)
        ->orwhere('status' , 0 )->onlyTrashed()->get();

        $res = array();
        $i = 0;
        foreach ($stores as $value) {
            $res[$i] = new StoreTasneemResource($value);
            $i++;
        }
        return $this->sendResponse($res, 'success');
    }

    ///boshra
    public function dashBoardAdmin()
    {
        return $this->sendResponse(new DashBoardResource([]), 'success');
    }


    ///boshra
    public function rating_profile($store_id)
    {
        $modelOrObject = (object) ['store_id' => $store_id];

        return $this->sendResponse( [new RatingProfileResource($modelOrObject)] , 'success');
    }
}
