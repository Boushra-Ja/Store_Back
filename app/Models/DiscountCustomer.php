<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCustomer extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'discount_codes_id',
        'customers_id',
        'usage_times'
    ];
}
