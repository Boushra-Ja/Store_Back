<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'discription',
        'image',
        'store_id',

    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class , 'collection_id');
    }
}
