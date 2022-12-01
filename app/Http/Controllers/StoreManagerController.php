<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Models\Persone;
use App\Models\Privilladge;
use App\Models\PrivilladgeHelper;
use App\Models\Store;
use App\Models\StoreManager;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\WaitingStore;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;
use TheSeer\Tokenizer\Token;


class StoreManagerController extends BaseController
{

    /////عرض معلومات صاحب متجر محدد
    /// bayan
    public function index($id)
    {
        $storeManager = StoreManager::where('id', '=', $id)->first();
        $persone = Persone::where('id', '=', $storeManager->person_id)->first();
        if ($storeManager) {
            return $this->sendResponse($persone, 'Store Shop successfully');
        } else {
            return $this->sendErrors('failed in Store Shop', ['error' => 'not Store Shop']);

        }
    }

    //////انشاء حساب صاحب متجر
    /// bayan
    public static function register(Request $request, $store_id, int $new_or_helper,$image)
    {

        //new  1
        //helper  2

        $valid = $request->validate([
            'username' => 'required ',
            'email' => 'required | unique:users',
            'password' => 'required',
        ]);

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $pin = mt_rand(1000, 9999)
            . $characters[rand(0, strlen($characters) - 1)]
            . $characters[rand(0, strlen($characters) - 1)];


        $code = str_shuffle($pin);


        $persone = Persone::create([
            'name' => $valid['username'],
            'email' => $valid['email'],
            'password' => $valid['password'],
            'code' => $code,
        ]);
        if ($request->image)
            $persone->update([
                'image' => $image,
            ]);


        if ($persone) {
            $token = $persone->createToken('StoreManagerToken')->plainTextToken;
            $persone->save();

            $user1 = StoreManager::create([
                'person_id' => $persone->id,
                'store_id' => $store_id,
            ]);

            $user1->save();


            mailcontrol::html_email($persone->name, $code, $persone->email, 'التحقق من البريد الالكتوني');

            return $user1->id;

        }


    }


    ///bayan
    public function unique_email(Request $request)
    {
        $person = Persone::where('email', '=', $request->email)->first();
        if ($person) {
            return $this->sendResponse("error", 'The Email already exists');
        } else
            return $this->sendResponse("success", 'The Email is unique');

    }


    //bayan
    public static function update(Request $request)
    {
        if ($request->password)
            Persone::where('id', '=', $request->persone_id)->first()->update([
                'name' => $request->username,
                'email' => $request->email,
                'password' => $request->password

            ]);
        else
            Persone::where('id', '=', $request->persone_id)->first()->update([
                'name' => $request->username,
                'email' => $request->email,
            ]);

        if ($request->helper_name)
            HelperController::store($request);
    }


    ///bayan
    public function true_password(Request $request)
    {
        $persone = Persone::where('id', '=', $request->persone_id)->first();
        if ($persone)
            if ($persone->password == $request->old_password)
                return $this->sendResponse("success", 'كلمة السر مطابقة');
            else
                return $this->sendResponse("erorr", 'كلمة السر غير مطابقة');
    }
    ///// تسجيل الدخول كصاحب متجر
    /// bayan
    public function login(Request $request)
    {

        $valid = $request->validate([
            'email' => 'required',
            'password' => 'required|min:3|max:100',
        ]);

        $person = Persone::where('email', '=', $valid['email'])->first();
        if ($person)
            if ($person->password == $valid['password']) {
                $storManager = StoreManager::where('person_id', '=', $person->id)->first();
                $token = $person->createToken('ProductsTolken')->plainTextToken;
                $new_or_helper = Helper::where('email', '=', $request->email)->first();

                $privilladge = array();
                if ($new_or_helper) {
                    $i = 0;
                    $p = PrivilladgeHelper::where('helper_id', '=', $new_or_helper->id)->get();
                    foreach ($p as $item) {
                        $privilladge[$i] = Privilladge::where('id', '=', $item->privilladge_id)->value('id');
                        $i += 1;
                    }
                } else {
                    $i = 0;
                    $p = Privilladge::all();
                    foreach ($p as $item) {
                        $privilladge[$i] = $item->id;
                        $i += 1;
                    }
                }
                $store = Store::where('id', '=', StoreManager::where('person_id', '=', $person->id)->value('store_id'))->first();
                if ($store)
                    if (!$store->status)
                        return response()->json([
                            'message' => 'wait',
                        ]);

                $store = Store::onlyTrashed()->where('id', '=', StoreManager::where('person_id', '=', $person->id)->value('store_id'))->first();
                if ($store)
                    return response()->json([
                        'message' => 'not_active',
                    ]);


                return response()->json([
                    'privilladge' => $privilladge,
                    'message' => 'success',
                    'manager_id' => $storManager->id,
                    'store_id' => $storManager->store_id,
                    'person_id' => $person->id,
                    'token'=> $token

                ]);

            } else {
                return response()->json([
                    'message' => 'erorr',
                ]);
            }
    }

    ////////التحقق من البريد
    /// //bayan
    public function verify_email(Request $request)
    {
        $persone = Persone::where('id', '=', StoreManager::where('id', '=', $request->manager_id)->value('person_id'))->first();
        if ($persone) {
            if (strcmp($persone->code, $request->code) == 0) {
                $store = WaitingStore::where('store_id', '=', $request->store_id)->first();
                $store->update(['true' => 1]);
                return response()->json([
                    'message' => 'true',
                ]);
            } else
                return response()->json([
                    'message' => 'false',
                ]);
        } else
            return response()->json([
                'message' => 'error',
            ]);


    }


    //bayan
    public function forget_password(Request $request)
    {
        $persone = Persone::where('email', '=', $request->email)->first();
        if ($persone) {

            mailcontrol::html_email_password($persone->name, $persone->email, 'اعادة تعين كلمة المرور', $persone->id);


        } else
            return response()->json([
                'message' => 'error',
            ]);

    }


    //bayan
    public function reset_password(int $id, string $new_password)
    {
        $persone = Persone::where('id', '=', StoreManager::where('id', '=', $id)->value('person_id'))->first();
        $persone->update(['password' => $new_password]);

    }


    public function my_Store_manager($id)
    {
        $s = StoreManager::where('id', '=', $id)->first();
        $person = Persone::where('id', '=', $s->person_id)->first();
        $stor = Store::where('id', '=', $s->store_id)->first();
        return response()->json(["person" => $person, "image" => $stor->Brand, "name_store" => $stor->name], 200);
    }

    public function batool($store_id)
    {
        $storeManager = StoreManager::where('store_id', '=', $store_id)->value('person_id');
            return $storeManager;

    }

}
