<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreVisitore extends Model
{
    use HasFactory;
    protected $fillable = [
        'visit_num',
        'store_id',
        'visit_date',
    ];
}
