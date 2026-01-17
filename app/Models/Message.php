<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'phone',
        'content',
        'status',
        'message_id',
        'sent_at',
    ];

    protected $casts = [
        'status' => \App\Enums\MessageStatus::class,
    ];
}
