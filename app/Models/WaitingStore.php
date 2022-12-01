<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaitingStore extends Model
{
    use HasFactory;



    protected $fillable = [
        'store_id','true'
    ];

    public function storeManager()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
