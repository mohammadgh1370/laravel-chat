<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'body',
    ];

    protected $with = ['sender', 'receiver'];

    protected $appends = ['selfMessage'];

    public function scopeBySender($q, $sender)
    {
        $q->where('sender_id', $sender);
    }

    public function scopeByReceiver($q, $sender)
    {
        $q->where('receiver_id', $sender);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id')->select(['id', 'name']);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id')->select(['id', 'name']);
    }

    public function getSelfMessageAttribute()
    {
        return auth()->id() == $this->sender_id;
    }
}
