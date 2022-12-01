<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Customer;
use App\Models\DiscountCode;
use App\Models\DiscountCustomer;
use App\Http\Requests\StoreDiscountCustomerRequest;
use App\Http\Requests\UpdateDiscountCustomerRequest;
use App\Http\Resources\BoshraRe\DiscountResource;
use App\Models\Order;
use App\Models\SecondrayClassification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountCustomerController extends BaseController
{
    public static function store(int $discount_codes_id, int $customers_id)
    {
        //        $discount_codes = DiscountCode::find($discount_codes_id);
        //        $customers = Customer::find($customers_id);

        //$response = $discount_codes->Customers()->attach($customers);

        $response = DiscountCustomer::Create([
            'customers_id' => $customers_id,
            'discount_codes_id' => $discount_codes_id,
        ]);

        return response()->json($response, 200);
    }

    public static function apply_dis($order_id , Request $request){

        $response = Order::where('id' , $order_id)->update([
            'discount_codes_id' => $request->discount_codes_id,
        ]);

        return response()->json($response, 200);
    }


    //boshra
    public function myDiscount($customer_id)
    {
        $customer_discount = DB::table('discount_customers')->where('customers_id', Customer::where('persone_id', $customer_id)->value('id'))
            ->join('discount_codes', 'discount_codes.id', '=', 'discount_customers.discount_codes_id')
            ->join('discounts', 'discounts.id', '=', 'discount_codes.discounts_id')
            //  ->where('discounts.end_date' , '>' , [Carbon::now()->format('Y-m-d')])

            ->select("discount_customers.id as discount_customers_id", "discount_customers.customers_id", 'discount_codes.*', 'discounts.*')
            ->get();

        return $this->sendResponse(DiscountResource::collection($customer_discount), 'success');
    }
    //boshra
    public function discount_store($customer_id , $store_id)
    {
        $customer_discount = DB::table('discount_customers')->where('customers_id', Customer::where('persone_id', $customer_id)->value('id'))
            ->join('discount_codes', 'discount_codes.id', '=', 'discount_customers.discount_codes_id')
            ->join('discounts', 'discounts.id', '=', 'discount_codes.discounts_id')
            ->where('discounts.store_id' , $store_id)
            ->where('discounts.end_date' , '>' , [Carbon::now()->format('Y-m-d')])
            ->select("discount_customers.id as discount_customers_id", "discount_customers.customers_id", 'discount_codes.*', 'discounts.*')
            ->get();

        return $this->sendResponse(DiscountResource::collection($customer_discount), 'success');
    }


    ///boshra
    public function delete_discount($dis_cus_id)
    {
        $res = DiscountCustomer::where('id', $dis_cus_id)->delete();
        if ($res)
            return $this->sendResponse($res, "success");
        else
            return $this->sendErrors([], "failed");
    }
}
