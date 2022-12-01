<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecondrayClassification extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'title',
        'classification_id',

    ];

    public function classification()
    {
        return $this->belongsTo(Classification::class, 'classification_id');
    }

//    public function classification_products()
//    {
//        return $this->hasMany(SecondrayClassification::class , 'classification_id');
//    }

    public function product()
    {
        return $this->belongsToMany(Product::class,'secondray_classification_products','secondary_id','product_id','id','id') ;
    }

}
