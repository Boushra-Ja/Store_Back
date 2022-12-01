<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class Persone extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class) ;
    }

    public function store_manager()
    {
        return $this->hasOne(StoreManager::class) ;
    }

    public function notifications_send()
    {
        return $this->belongsToMany(Persone::class , 'notifications') ;
    }

    public function notifications_recive()
    {
        return $this->belongsToMany(Persone::class) ;
    }

    public function messages_send()
    {
        return $this->belongsToMany(Chat::class , 'chats') ;
    }

    public function messages_recieve()
    {
        return $this->belongsToMany(Chat::class) ;
    }
}
