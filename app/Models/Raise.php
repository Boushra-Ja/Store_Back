<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Raise extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'price',
        'date',
        'status',
        'product_id',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function customer()
    {
        return $this->belongsToMany(Customer::class, 'customer_raises', 'raise_id', 'customer_id');
    }
}
