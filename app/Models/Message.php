<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'sender_id',
        'message',
        'chats_id'
    ];


    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chats_id');
    }

}
