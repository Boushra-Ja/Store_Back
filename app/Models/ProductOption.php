<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOption extends Model
{

    protected $fillable = [
        'order_product_id',
        'option_values_id'];

    use HasFactory;
    use SoftDeletes;

}
