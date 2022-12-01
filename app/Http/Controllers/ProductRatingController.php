<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\ProductRating;
use App\Http\Requests\StoreProductRatingRequest;
use App\Http\Resources\BoshraRe\RatingResource;
use App\Models\Customer;

class ProductRatingController extends BaseController
{
    //عرض جميع تقييمات المنتجات
    ///bohsra
    public function index()
    {
        $rating = ProductRating::all();
        if ($rating) {
            return  $this->sendResponse(RatingResource::collection($rating), 'تم ارجاع جميع التقييمات بنجاح');
        } else {
            return $this->sendErrors("خطأ في عرض التقييمات",  ['error' => 'error in display ratings']);
        }
    }
    /////تقييم منتج من قبل الزبون
    ///boshra
    public function store(StoreProductRatingRequest $request)
    {
        $input = $request->all();
        $rating = ProductRating::create($input);

        if ($rating) {
            return $this->sendResponse(new RatingResource($rating), 'نجحت عملية تقييم المنتج');
        } else {
            return $this->sendErrors('فشل في عملية التقييم', ['error' => 'not rating store']);
        }
    }

    //boshra
    public function isRating($product_id , $customer_id)
    {
        $check = ProductRating::where('customer_id' , Customer::where('persone_id'  , $customer_id)->value('id'))->where('product_id' , $product_id)->first()  ;
        if($check)
            return 1 ;
        else
            return 0 ;
    }
}
