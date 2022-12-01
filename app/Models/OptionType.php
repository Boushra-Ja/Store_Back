<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionType extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'product_id',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function option_values()
    {
        return $this->hasMany(OptioinValue::class , 'option_type_id');
    }
}
