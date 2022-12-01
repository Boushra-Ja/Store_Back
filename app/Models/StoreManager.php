<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class StoreManager extends Model
{

    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
       'privilladge',
       'store_id',
        'person_id',
    ];

    public function persone()
    {
        return $this->belongsTo(Persone::class  , 'persone_id') ;
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function privilladge()
    {
        return $this->belongsToMany(Privilladge::class,'privilladge_store_managers','store_manager_id','privilladge_id') ;
    }

    public function helper()
    {
        return $this->hasMany(Helper::class , 'store_manager_id');
    }




}
