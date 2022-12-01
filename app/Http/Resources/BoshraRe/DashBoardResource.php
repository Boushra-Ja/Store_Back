<?php

namespace App\Http\Resources\BoshraRe;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Report;
use App\Models\Store;
use App\Models\WaitingStore;
use Illuminate\Http\Resources\Json\JsonResource;

class DashBoardResource extends JsonResource
{

    public function toArray($request)
    {

        $stores = Store::where('status' , 1)->get() ;
        $list = StoreTasneemResource::collection($stores) ;
        $statisticCollection = collect($list);
        $sorted = $statisticCollection->sortByDesc('review');


        $customers = Customer::all() ;
        $list_cus = CustomerResource::collection($customers) ;
        $cusCollection = collect($list_cus);
        $sorted_cus = $cusCollection->sortByDesc('total_price');

        return [
            'num_stores' => Store::where('status' , 1)->count() ,
            'num_customers' => Customer::count(),
            'num_reports' => Report::count() ,
            'num_login' => WaitingStore::where('true' , 1)->count(),
            'Accepted_orders' =>  OrdersResource::collection(Order::where('status_id' , OrderStatus::where('status' , 'مقبول')->value('id'))->get()),
            'best_store' => $sorted->values()->take(10) ,
            'best_customer' => $sorted_cus->values()->take(10)
        ];
    }
}
