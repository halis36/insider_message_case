<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'phone_number',
        'content',
        'status',
        'message_id',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
