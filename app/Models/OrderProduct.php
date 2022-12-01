<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'status_id',
        'discount_products_id',
        'amount',
        'gift_order',
        //'discount_codes_id'

    ];


    public function product_values()
    {
        return $this->belongsToMany(OptioinValue::class, 'product_options');
    }


    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function discount_product()
    {
        return $this->belongsTo(DiscountProduct::class, 'discount_products_id');
    }



}
