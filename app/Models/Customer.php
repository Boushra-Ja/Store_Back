<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;


    public $table = "customers";

    protected $fillable = [
        'persone_id',
    ];

    public function persone()
    {
        return $this->belongsTo(Persone::class, 'persone_id');
    }


    public function favourit_products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function favourit_stors()
    {
        return $this->belongsToMany(Store::class);
    }

    public function rating_store()
    {
        return $this->belongsToMany(Store::class);
    }

    public function report_stors()
    {
        return $this->belongsToMany(Store::class);
    }

    public function discount_codes()
    {
        return $this->belongsToMany(DiscountCode::class, 'discount_customers', 'customers_id', 'discount_codes_id', 'id', 'id');
    }

    public function rating_products()
    {
        return $this->belongsToMany(Product::class);
    }


    public function orders()
    {
        return $this->belongsToMany(Store::class, 'orders');
    }

    public function raise()
    {
        return $this->belongsToMany(Raise::class, 'customer_raises', 'customer_id', 'raise_id');
    }

}
