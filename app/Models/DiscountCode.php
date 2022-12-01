<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCode extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'discount_code',
        'discounts_id',
        'condition',
        'condition_value',
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class  , 'discounts_id') ;
    }

    public function Customers()
    {
        return $this->belongsToMany(Customer::class,'discount_customers','discount_codes_id','customers_id','id','id' ) ;
    }

    public function order()
    {
        return $this->hasMany(Order::class , 'discount_codes_id');
    }
}
