<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use HasFactory;

    protected  $table = 'order_statuses' ;

    protected $fillable = [
        'id',
        'status',

    ];

    public function orders()
    {
        return $this->hasMany(OrderProduct::class , 'status_id');
    }
    ///new boshra
    public function order()
    {
        return $this->hasMany(Order::class , 'status_id');
    }
}
