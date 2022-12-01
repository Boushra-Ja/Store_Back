<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FavoriteStore extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'customer_id',
        'store_id'

    ];
}

