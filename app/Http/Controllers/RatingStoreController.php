<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\RatingStore;
use App\Http\Requests\StoreRatingStoreRequest;
use App\Http\Resources\BoshraRe\RatingResource;
use App\Models\Customer;
use App\Models\Order;

class RatingStoreController extends BaseController
{



    ///عرض جميع تقييمات المتاجر
    //boshra
    public function index()
    {
        $rating = RatingStore::all();
        if ($rating) {
            return $this->sendResponse(RatingResource::collection($rating), 'تم ارجاع جميع التقييمات بنجاح');
        } else {
            return $this->sendErrors("خطأ في عرض التقييمات",  ['error' => 'error in display ratings']);
        }
    }



    ////////تقيييم متجر من قبل الزبون
    //boshra
    public function store(StoreRatingStoreRequest $request)
    {
        $rating = RatingStore::create(
            [
                'customer_id' => Customer::where('persone_id' , $request->customer_id)->value('id'),
                'store_id' => $request->store_id,
                'notes' => $request->notes,
                'value' => $request->value,
            ]
        );

        if ($rating) {
            return $this->sendResponse(new RatingResource($rating), 'نجحت عملية التقييم');
        } else {
            return $this->sendErrors('فشل في عملية التقييم', ['error' => 'not rating store']);
        }
    }

    //boshra
    public function isRating($store_id , $customer_id)
    {
        /////بيان لما بيحولو الطلب لطلب تم تسليمه بتحذف تقييم هاد الزبون لمتجرها
        $c = Order::where('customer_id' , Customer::where('persone_id' , $customer_id)->value('id'))->where('store_id' , $store_id)->first()  ;
        if($c)
        {
            $check = RatingStore::where('customer_id' , Customer::where('persone_id' , $customer_id)->value('id'))->where('store_id' , $store_id)->first()  ;
            if($check)
                return 1 ;
            else
                return 0 ;
        }
        return 1 ;

    }
}
