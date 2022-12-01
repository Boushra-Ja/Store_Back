<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'title',
        'message',
        'sender_id',
        'receiver_id',
    ];
//    public function persones()
//    {
//        return $this->belongsToMany(Persone::class,'notifications','receiver_id');
//    }
////    public function favorite_products()
////    {
////        return $this->belongsToMany(Customer::class , 'favorite_products','product_id','customer_id',) ;
////    }
}
