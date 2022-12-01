<?php

namespace App\Http\Controllers;

use App\Http\ResourcesBat\ClassificationB;
use App\Http\ResourcesBayan\waitStore;
use App\Models\Collection;
use App\Models\Discount;
use App\Models\DiscountCode;
use App\Models\DiscountProduct;
use App\Models\Order;
use App\Models\Persone;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreManager;
use App\Models\WaitingStore;
use App\Http\Requests\StoreWaitingStoreRequest;
use App\Http\Requests\UpdateWaitingStoreRequest;
use http\Env\Request;

class WaitingStoreController extends Controller
{

    //bayan
    public static function store(int $store)
    {

        $waitingStore = WaitingStore::create([
            'store_id' => $store,
            'true' => '0'
        ]);
    }

    //bayan
    public function show_waite()
    {
        $store = WaitingStore::where('true', '=', '1')->get();
//        $a = array();
//        $i = 0;
//        foreach ($store as $item) {
//            $a[$i] = [Store::where('id', '=', $item->store_id)->first(), Persone::where('id', '=', StoreManager::where('store_id', '=', $item->store_id)->value('person_id'))->value('name')];
//            $i += 1;
//        }
//        return $a;

        return waitStore::collection($store);

    }

    //bayan
    public function show_active()
    {
        $a = Store::where('status', '=', '1')->get();

        return $a;
    }

    //bayan
    public function show_deactive()
    {
        $store = Store::onlyTrashed()->get();

        return $store;
    }

    //bayan
    public function accept_store($store)
    {
        $stor = WaitingStore::where('store_id', '=', $store)->first();
        $s = Store::where('id', '=', $store)->first();
        $s->update(['status' => 1]);
        $persone = Persone::where('id', '=', StoreManager::where('store_id', '=', $store)->value('person_id'))->first();
        mailcontrol::accept_store($persone->name, $persone->email, 'تم فبول متجرك');
        $stor->delete();
    }

    //bayan
    public function reject_store($store)
    {
        $stor = Store::where('id', '=', $store)->first();
        $person = Persone::where('id', '=', StoreManager::where('store_id', '=', $store)->value('person_id'))->first();
        $stor->forceDelete();
        $person->forceDelete();


    }

    //bayan
    public function deactivate_store($store)
    {
        $stor = Store::where('id', '=', $store)->first();
        $stor->update(['status' => '0']);

        $collection = Collection::where('store_id', '=', $store)->get();
        foreach ($collection as $c) {
            $product = Product::where('collection_id', '=', $c->id)->get();
            foreach ($product as $p)
                $p->delete();
            $c->delete();
        }
        $discount = Discount::where('store_id', '=', $store)->get();
        foreach ($discount as $d) {
            if ($d->type == 1)
                $dp = DiscountProduct::where('discounts_id', $d->id)->first();

            else
                $dp = DiscountCode::where('discounts_id', $d->id)->first();

            $dp->delete();
            $d->delete();

        }
        $order = Order::where('store_id', $store)->get();
        foreach ($order as $o) {
            $o->delete();
        }
        $stor->delete();
    }

    //bayan
    public function activate_store($store)
    {
        $stor = Store::onlyTrashed()->where('id', '=', $store)->first();
        $stor->update(['status' => '1']);

        $collection = Collection::onlyTrashed()->where('store_id', '=', $store)->get();
        foreach ($collection as $c) {
            $product = Product::onlyTrashed()->where('collection_id', '=', $c->id)->get();
            foreach ($product as $p)
                $p->restore();
            $c->restore();
        }
        $discount = Discount::onlyTrashed()->where('store_id', '=', $store)->get();
        foreach ($discount as $d) {
            if ($d->type == 1)
                $dp = DiscountProduct::onlyTrashed()->where('discounts_id', $d->id)->first();

            else
                $dp = DiscountCode::onlyTrashed()->where('discounts_id', $d->id)->first();

            $dp->restore();
            $d->restore();

        }
        $order = Order::onlyTrashed()->where('store_id', '=', $store)->get();
        foreach ($order as $o) {
            $o->restore();
        }

        $stor->restore();

    }

}
