<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\ResourcesBayan\customerResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersoneRequest;
use App\Http\Resources\BoshraRe\CustomerResource as BoshraReCustomerResource;
use App\Models\Customer;
use App\Models\Persone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class CustomerController extends BaseController
{


    public static function html_email(string $name, string $code, string $email, string $title)
    {
        $data = array('name' => $name, 'code' => $code);
        Mail::send('mail', $data, function ($message) use ($title, $email) {
            $message->to($email, 'متجر جديد')->subject
            ($title);
            $message->from('aseel.2016.batoul@gmail.com', 'مشروعي');
        });
        echo "HTML Email Sent. Check your inbox.";
    }

    public function basic_email()
    {
        $data = array('name' => "Virat Gandhi");

        Mail::send(['text' => 'mail'], $data, function ($message) {
            $message->to('faizzoubi30@gmail.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
            $message->from('faizzoubi10@gmail.com', 'Virat Gandhi');
        });
        echo "Basic Email Sent. Check your inbox.";
    }


    public function verify_email(Request $request)
    {
        $persone = Persone::where('email', '=', $request->persone)->first();
        if ($persone) {

            if (strcmp($persone->code, $request->code) == 0)
                echo "ssssssssss";
            else
                echo "fffffffffff";
        } else
            echo "dose not exists";


    }


    function register(Request $request)
    {

        $valid = $request->validate([
            'name' => 'required ',
            'email' => 'required | unique:users',
            'password' => 'required',
            'code' => 'nullable',
            'image' => 'nullable',


        ]);

        $persone = Persone::create([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'password' => $valid['password'],

        ]);
//        if($request->code!=null)
//            $persone->code=$request->code;
//        if($request->image!=null)
//            $persone->image=$request->image;
//        $persone->save();

        $user1 = Customer::create([
            'persone_id' => $persone->id,

        ]);


        $user1->save();


        $token = $persone->createToken('ProductsTolken')->plainTextToken;
        return response()->json([
            'user' => $persone,
            'token' => $token,
        ]);


    }


    function login(Request $request)
    {

        $valid = $request->validate([
            'email' => 'required',
            'password' => 'required|min:3|max:100',

        ]);

        $person = Persone::where('email', $valid['email'])->first();
        ///echo $personm;
        if ($valid['password'] == $person->password)
            $password = true;
        if (!$person || !$password) {
            return response()->json(['message' => 'Login problem']);
        } else {
            $token = $person->createToken('ProductsTolken')->plainTextToken;
            return response()->json([
                'user' => $person,
                'token' => $token,
            ]);
        }
    }


    function logout(Request $request)
    {


        $result = $request->user()->token()->revoke();
        if ($result) {
            $response = response()->json('User logout successfully', 200);
        } else {
            $response = response()->json('Something is wrong', 400);
        }
        return $response;

    }


    function change_password(Request $request)
    {

        $validator = Validator:: make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:3|max:100',
           // 'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'message' => 'Validations fails',
                'errors' => $validator->errors()], 422);
        } else {

            $password = Persone::query()->get()->pluck(['password']);
            if ($password[0] == $request->old_password) {
                $usermodel = Persone::find('3');
                if ($usermodel) {
                    $usermodel->password = $request->password;
                    $usermodel->save();
                    return response()->json(['message' => 'Password successfully updated',], 200);
                }
            } else
                return response()->json(['message' => 'no match password with old ',], 422);

        }


    }



    //bayan
    public static function myCustomer($id)
    {

        $order = Order::where('store_id', '=', $id)->get();

        $d = $order->groupBy('customer_id');


        $array = customerResource::collection($d);
        return $array;


    }
    //bayan
    public function myCustomer_most_buy($id)
    {
        $data = CustomerController::myCustomer($id);
        $array = collect($data)->sortBy('orders')->reverse()->toArray();

        return response()->json($array);
    }

    //bayan
    public function myCustomer_salles($id)
    {
        $data = CustomerController::myCustomer($id);
        $array = collect($data)->sortBy('total')->reverse()->toArray();

        return response()->json($array);
    }

    public function EditMyProfile($id , StorePersoneRequest $request)
    {

        if(Persone::find($id)->update($request->all()))
        {
            $data = Persone::where('id' , $id)->get() ;

            if($request->hasfile('image'))
                return $this->sendResponse($data, 'success');
        }
        return $this->sendErrors([], 'error');
    }

    ///boshra
    public function allCustomers()
    {

        $customers = Customer::all();
        return $this->sendResponse(BoshraReCustomerResource::collection($customers) , 'success') ;

    }
}
