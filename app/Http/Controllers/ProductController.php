<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Http\ResourcesBat\RatingProB;
use App\Models\Discount;
use App\Models\DiscountProduct;
use App\Models\Product;
use App\Http\Resources\BoshraRe\ProductAllResource;
use App\Http\Resources\BoshraRe\ProductResource;
use App\Http\ResourcesBat\ClassificationDiscountB;
use App\Http\ResourcesBayan\one_product_show;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\OptioinValue;
use App\Models\OptionType;
use App\Models\SecondrayClassificationProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{

    //الاقل سعرا//
    public function Order_Salary()
    {
        $ProductModel = Product::orderBy('cost_price', 'asc')->get();
        return RatingProB::collection($ProductModel->take(10));
    }

    //الاكثر شيوعا//
    public function Order_sales()
    {
        $ProductModel = Product::orderBy('number_of_sales', 'desc')->get();
        return RatingProB::collection($ProductModel->take(10));
    }

    //العروض والحسومات//
    public function Order_discount()
    {

        $ProductModel = DB::table('discount_products')
            ->join('products', function ($join) {
                $join->on('discount_products.id', '=', 'products.discount_products_id')
                    ->where('discount_products.title', '=', 'null');
            })
            ->get();

        return RatingProB::collection($ProductModel);
    }

    //اقتراحات قد تعجبك//
    public function Order_favorite()
    {



        $re = array();
        $i = 0;
        $pro = DB::table('secondray_classification_products')
            ->join('favorite_products', 'favorite_products.product_id', '=', 'secondray_classification_products.product_id')
            ->where('customer_id' ,'=',Customer::where('persone_id' , Auth::id())->value('id')
            )

            ->get();

        foreach ($pro as $val) {
            $prooo = DB::table('secondray_classification_products')
                ->join('products', 'products.id', '=', 'secondray_classification_products.product_id')
                ->where('secondray_classification_products.secondary_id', '=', $val->secondary_id)->get();
            foreach ($prooo as $valk) {

                $re[$i] = $valk;
                $i++;
            }
        }
        return RatingProB::collection($re);
    }


    //كل النتجات//
    public function Product_All()
    {
        $ProductModel = Product::query()->get();
        return response()->json($ProductModel, 200);
    }

    //تفاصيل منتج واحد//
    public function Show_Detalis($id)
    {
        $ProductModel = Product::query()->where('id', $id)->get();
        return response()->json($ProductModel, 200);
    }


    public function index()
    {
        $ProductModel = Product::all();
        return response()->json(ProductAllResource::collection($ProductModel), 200);
    }

    /// bayan
    public function myshow($id)
    {
        $data = Product::where('id', '=', $id)->get();
        if ($data) {
            $g = one_product_show::collection($data);
            return response()->json($g[0], 200);
        } else {
            return $this->sendErrors('خطأ في عرض معلومات المنتج', ['error' => 'error in show product info']);
        }
    }

    ////عرض منتج محدد
    //boshra
    public function show($id)
    {
        $data = Product::where('id', $id)->get();
        if ($data) {
            return $this->sendResponse(ProductAllResource::collection($data), 'تم ارجاع معلومات المنتج بنجاح');
        } else {
            return $this->sendErrors('خطأ في عرض معلومات المنتج', ['error' => 'error in show product info']);
        }
    }

    //////عرض منتجات مشابهة
    public function similar_products($id)
    {
        $my_classification = SecondrayClassificationProduct::where('product_id', $id)->get();

        $products_class_ids = array();
        $i = 0;
        $res = array();
        $j = 0;
        foreach ($my_classification as $value) {
            $products_class_ids[$i] = DB::table('secondray_classification_products')->where('secondary_id', $value['secondary_id'])->where('product_id', '!=', $id)
                ->join('products', 'products.id', '=', 'secondray_classification_products.product_id')
                ->get();

            foreach ($products_class_ids[$i] as $val) {
                $res[$j] = $val;
                $j++;
            }
            $i++;
        }


        return $this->sendResponse(ProductResource::collection($res), "success");
    }

    // اضافة منتج
    //bayan
    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required',
            'discription' => 'nullable',
            'image' => 'required',
            'selling_price' => 'required',
            'cost_price' => 'required',
            'collection_id' => 'required',
            'return_or_replace' => 'required',
            'prepration_time' => 'required',
            'gift' => 'nullable',
            'number_of_sales' => 'nullable',
            'party' => 'nullable',
            'age' => 'nullable',
        ]);

        //        if ($request->hasfile('image')) {
        //            $file = $request->file('image');
        //            $extention = $file->getClientOriginalExtension();
        //            $filename = time() . '.' . $extention;
        //            $file->move('uploads/books/', $filename);
        //            $request->image = $filename;
        //
        //        } else
        //            $request->image = '';


        $i = DiscountProduct::where('discounts_id', '=', (Discount::where('store_id', '=', $request->store_id)->where('type', '=', '1')->where('value', '=', '0')->value('id')))->value('id');
        $request->number_of_sales = 0;

        $product = Product::create([
            'name' => $request->name,
            'discription' => $request->discription,
            'image' => $this->storeImage($request->image, $request->name),
            'selling_price' => $request->selling_price,
            'cost_price' => $request->cost_price,
            'collection_id' => $request->collection_id,
            'return_or_replace' => $request->return_or_replace,
            'prepration_time' => $request->prepration_time,
            'gift' => $request->gift,
            'party' => $request->party,
            'age' => $request->age,
            'discount_products_id' => $i,
            'number_of_sales' => 0
        ]);

        if ($product) {
            if ($request->classification) {

                $j = json_decode($request->classification);

                foreach ($j as $value) {
                    SecondrayClassificationProductController::store($product->id, $value);
                }
            }

            if ($request->type) {

                $j = json_decode($request->type);

                foreach ($j as $vv) {

                    OptionTypeController::store($vv, $product->id, 0);
                }
            }

            return $this->sendResponse($product, 'Store Shop successfully');
        } else {
            return $this->sendErrors('failed in Store Shop', ['error' => 'not Store Shop']);
        }
    }
    ///صورة
    /// bayan
    public function storeImage($image, $title)
    {
        $newImageName = uniqid() . '-' . $title . '.' . $image->extension();
        $image->move(public_path('uploads\product'), $newImageName);
        return $newImageName;
    }


    // تعديل منتج
    //bayan
    public function update(Request $request)
    {

        $product = Product::where('id', '=', $request->id)->first();
        $product->update([
            'name' => $request->name,
            'prepration_time' => $request->prepration_time,
            'party' => $request->party,
            'discription' => $request->discription,
            'image' => $this->storeImage($request->image, $request->name),
            'age' => $request->age,
            'gift' => $request->gift,
            'collection_id' => $request->collection_id,
            'selling_price' => $request->selling_price,
            'cost_price' => $request->cost_price,
            'return_or_replace' => $request->return_or_replace,
        ]);

        if ($request->classification) {
            $j = json_decode($request->classification);

            $secondrayClassification = SecondrayClassificationProduct::where('product_id', '=', $request->id)->delete();

            foreach ($j as $value) {
                SecondrayClassificationProductController::update($product->id, $value);
            }
        }

        if ($request->type) {

            foreach ($request->type as $vv) {

                OptionTypeController::update($vv, $product->id);
            }
        }
        return $this->sendResponse($product, 'تم تعديل المجموعة بنجاح');
    }

    //حذف منتج
    //bayan
    public function delete(Request $request)
    {
        $product = Product::where('id', '=', $request->id)->delete();
    }


    ///boshra
    public function my_product($store_id)
    {

        $collections_id = Collection::where('store_id', $store_id)->get();
        $product = array();
        $i = 0;
        $j = 0;
        $res = array();

        foreach ($collections_id as $value) {

            $product[$i] = Product::where('collection_id', $value['id'])->get();

            foreach ($product[$i] as $val) {
                $res[$j] = $val;
                $j = $j + 1;
            }
            $i = $i + 1;
        }

        return $this->sendResponse(ProductResource::collection($res), 'success');
    }

    //boshra
    public function Gift_request($party, $age, $material, $fromprice, $toprice)
    {
        $data4 = array();
        $data5 = array();
        $res = array();
        $k = 0;
        $i = 0;
        $j = 0;
        if ($material != 'null') {
            $data = OptioinValue::query()
                ->where('value', $material)
                ->get();

            foreach ($data as $value) {

                $data4[$i] = OptionType::where('id', $value['option_type_id'])->get();

                foreach ($data4[$i] as $val) {
                    if ($party != " " && $age != ' ' && $fromprice != '0' && $toprice != '0') {
                        $data5[$j] = Product::where('id', $val['product_id'])
                            ->where('party', 'LIKE', '%' . $party . '%')
                            ->where('age', '==', $age)
                            ->where('selling_price', '>=', $fromprice)
                            ->where('selling_price', '<=', $toprice)
                            ->get();
                    } else if ($party == " " && $age == ' ' && $fromprice == '0' && $toprice == '0') {
                        $data5[$j] = Product::where('id', $val['product_id'])->get();
                    } else if ($party == " " && $age != ' ' && $fromprice != '0' && $toprice != '0') {
                        $data5[$j] = Product::where('id', $val['product_id'])
                            ->where('age', '==', $age)
                            ->where('selling_price', '>=', $fromprice)
                            ->where('selling_price', '<=', $toprice)
                            ->get();
                    } else if ($party != " " && $age == ' ' && $fromprice == '0' && $toprice == '0') {
                        $data5[$j] = Product::where('id', $val['product_id'])
                            ->where('party', 'LIKE', '%' . $party . '%')
                            ->get();
                    } else if ($party == " " && $age == ' ') {
                        $data5[$j] = Product::where('id', $val['product_id'])
                            ->where('selling_price', '>=', $fromprice)
                            ->where('selling_price', '<=', $toprice)
                            ->get();
                    } else if ($party == " " && $fromprice == "0" && $toprice == "0") {
                        $data5[$j] = Product::where('id', $val['product_id'])
                            ->where('age', '==', $age)
                            ->get();
                    } else if ($party != " " && $age == " ") {
                        $data5[$j] = Product::where('id', $val['product_id'])
                            ->where('party', 'LIKE', '%' . $party . '%')
                            ->where('selling_price', '>=', $fromprice)
                            ->where('selling_price', '<=', $toprice)
                            ->get();
                    } else if ($party != " " && $fromprice == "0" && $toprice == "0") {
                        $data5[$j] = Product::where('id', $val['product_id'])
                            ->where('party', 'LIKE', '%' . $party . '%')
                            ->where('age', '==', $age)
                            ->get();
                    }


                    foreach ($data5[$j] as $v) {
                        $res[$k] = $v;
                        $k++;
                    }
                    $j++;
                }
                $i++;
            }
        } else {
            if ($party != " " && $age != ' ' && $fromprice != '0' && $toprice != '0') {
                $data5[$j] = Product::where('party', 'LIKE', '%' . $party . '%')
                    ->where('age', '==', $age)
                    ->where('selling_price', '>=', $fromprice)
                    ->where('selling_price', '<=', $toprice)
                    ->get();
            } else if ($party == " " && $age == ' ' && $fromprice == '0' && $toprice == '0') {
                $data5[$j] = Product::all();
            } else if ($party == " " && $age != ' ' && $fromprice != '0' && $toprice != '0') {
                $data5[$j] = Product::where('age', $age)
                    ->where('selling_price', '>=', $fromprice)
                    ->where('selling_price', '<=', $toprice)
                    ->get();
            } else if ($party != " " && $age == ' ' && $fromprice == '0' && $toprice == '0') {
                $data5[$j] = Product::where('party', 'LIKE', '%' . $party . '%')
                    ->get();
            } else if ($party == " " && $age == " ") {
                $data5[$j] = Product::where('selling_price', '>=', $fromprice)
                    ->where('selling_price', '<=', $toprice)
                    ->get();
            } else if ($party == " " && $fromprice == "0" && $toprice == "0") {
                $data5[$j] = Product::where('age', $age)
                    ->get();
            } else if ($party != " " && $age == " ") {
                $data5[$j] = Product::where('party', 'LIKE', '%' . $party . '%')
                    ->where('selling_price', '>=', $fromprice)
                    ->where('selling_price', '<=', $toprice)
                    ->get();
            } else if ($party != " " && $fromprice == "0" && $toprice == "0") {
                $data5[$j] = Product::where('party', 'LIKE', '%' . $party . '%')
                    ->where('age', $age)
                    ->get();
            }
            foreach ($data5[$j] as $v) {
                $res[$k] = $v;
                $k++;
            }
        }
        return $this->sendResponse(ProductResource::collection($res), 'success');
    }

    ///boshra
    public function search_by_name($name , $i)
    {
        if($name == " ")
        {
            return RatingProB::collection(Product::all());
        }
         //sales الاكثر شيوعا//
        if ($i == '1') {

            $pro = DB::table('products')
            ->join('secondray_classification_products' , 'secondray_classification_products.product_id' , 'products.id')
            ->where('products.name', 'LIKE', '%' . $name . '%')
            ->orderBy('products.number_of_sales', 'desc')->get();

            return RatingProB::collection($pro->take(1));

        }
        //الاقل سعراsalary //
        if ($i == 2) {

            $pro = DB::table('products')->where('name', 'LIKE', '%' . $name . '%')
            ->join('secondray_classification_products' , 'secondray_classification_products.product_id' , 'products.id')
            ->orderBy('products.selling_price', 'asc')
            ->get();

            return RatingProB::collection($pro->take(1));
        }

        //العروض والخصوماتdiscount //
        if ($i == '3') {

            $pro = DB::table('discount_products')
                ->join('products' , 'discount_products.id', '=', 'products.discount_products_id')
                ->where('products.name', 'LIKE', '%' . $name . '%')
                ->where('discount_products.title', '=', 'null')
                ->get();
            return ClassificationDiscountB::collection($pro);

        }

         // اقتراحات قد تعجبكfavorite  //
        if ($i == '4') {
            $i=0;

            $pro = DB::table('secondray_classification_products')->select('*')
                ->join('favorite_products', 'favorite_products.product_id', '=', 'secondray_classification_products.product_id')
                ->join('products', 'favorite_products.product_id', '=', 'products.id')
                ->where('products.name', 'LIKE', '%' . $name . '%')
                ->get();


            return RatingProB::collection($pro);

        }

    }

    public function review_products()
    {
        $products = Product::all() ;
        return $this->sendResponse(ProductResource::collection($products) , 'success') ;
    }
}
