<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrivilladgeStoreManager extends Model
{
    use HasFactory;
   // use SoftDeletes;


    protected $fillable = [
        'store_manager_id',
        'privilladge_id',
    ];
}
