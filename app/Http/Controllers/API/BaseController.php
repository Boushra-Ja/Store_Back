<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class BaseController extends Controller
{
    public function sendResponse( $result , $message)
    {
        $respons = [
            'status' => 'success' ,
            'data' => $result  ,
            'message' => $message
        ];
        return response()->json($respons , 200) ;
    }

    public function sendErrors($errors , $errorMessage = [] , $code = 404)
    {
        $respons = [
            'status' => 'error' ,
            'data' => $errors ,
        ];

        if(!empty($errorMessage))
            $respons['data'] = $errorMessage ;

        return response()->json($respons , $code) ;
    }
}
