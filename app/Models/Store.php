<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'name',
        'delivery_area',
        'discription',
        'image',
        'Brand',
        'num_of_salling',
        'facebook',
        'mobile',
        'status'
    ];

    public function collectios()
    {
        return $this->hasMany(Collection::class , 'store_id');
    }

    public function favourits()
    {
        return $this->belongsToMany(Customer::class , 'favorite_stores','store_id','customer_id',) ;

    }


    public function rating()
    {
        return $this->belongsToMany(Customer::class , 'rating_stores') ;
    }

    public function reports()
    {
        return $this->belongsToMany(Customer::class , 'reports') ;
    }



    public function managers()
    {
        return $this->hasMany(StoreManager::class , 'store_id');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class , 'store_id');
    }

    public function waitingStore()
    {
        return $this->hasMany(WaitingStore::class , 'store_id');
    }


}
