<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\DiscountProduct;
use App\Http\Requests\StoreDiscountProductRequest;
use App\Http\Requests\UpdateDiscountProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountProductController extends Controller
{
    public static function store($request, $id, $h)
    {

        if ($h == 1) {
            $discount = DiscountProduct::create([
                'title' => ".",
                'apply_to' => ".",
                'discounts_id' => $id,

            ]);

        } else {

            $discount = DiscountProduct::create([
                'title' => $request["title"],
                'apply_to' => $request["apply_to"],
                'discounts_id' => $id,

            ]);

            if ($discount) {
                if ($request["product"] != null) {
                    foreach ($request["product"] as $value) {
                        $product = Product::where('id', '=', $value)->first();
                        if ($product)
                            $product->update(['discount_products_id' => $discount->id,]);

                    }
                } else if ($request["groups"] != null) {
                    foreach ($request["groups"] as $group) {
                        $g = Collection::where('id', '=', $group)->first();
                        $pro = Product::where('collection_id', '=', $g->id)->update(['discount_products_id' => $discount->id,]);

                    }
                }
            }
        }
    }
}
