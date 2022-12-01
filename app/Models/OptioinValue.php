<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptioinValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'option_type_id',

    ];

    public function option_type()
    {
        return $this->belongsTo(OptionType::class, 'option_type_id');
    }

    public function order_products()
    {
        return $this->belongsToMany(OrderProduct::class) ;
    }
}
