<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Helper extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'name',
        'email',
        'store_manager_id',
        'status'
    ];

    public function storeManager()
    {
        return $this->belongsTo(StoreManager::class, 'store_manager_id');
    }

    public function privilladge()
    {
        return $this->belongsToMany(Privilladge::class,'privilladge_helpers','helper_id','privilladge_id') ;
    }
}
