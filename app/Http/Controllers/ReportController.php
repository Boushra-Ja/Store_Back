<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Http\Controllers\API\BaseController;
use App\Models\Report;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Http\Resources\BoshraRe\ReportResource;
use App\Models\Customer;

class ReportController extends BaseController
{
    ///boshra
    public function index()
    {
        $reports = Report::all() ;
        return $this->sendResponse(ReportResource::collection($reports ), 'success') ;
    }


    ///boshra
    public function store(StoreReportRequest $request)
    {
        $report = Report::create(
            [
                'customer_id'=>Customer::where('persone_id' , $request->customer_id)->value('id'),
                'store_id'=>$request->store_id ,
                'content'=>$request->content
            ]
        );

        if ($report) {
            broadcast(new NotificationEvent("تم الابلاغ عن متجر",31,"شو ما كان" ));
            return $this->sendResponse($report, 'نجحت عملية الابلاغ');
        } else {
            return $this->sendErrors('فشل في عملية الابلاغ', ['error' => 'not report on store']);
        }
    }


    //boshra
    public function show(Report $report)
    {
        //
    }
}
