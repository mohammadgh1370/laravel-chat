<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['avatar', 'friends'];

    /**
     * static method for create api_token to user
     * @return string
     */
    public function scopeCreateApi_token()
    {
        $api_token = Str::random(60);
        $this->api_token = $api_token;
        $this->save();
        return $api_token;
    }

    public function getAvatarAttribute()
    {
        return Cache::rememberForever('avatar' . $this->id, function () {
            return "https://ui-avatars.com/api/?name=" . $this->name . "&bold=true&background=" . $this->getAvatarBackground() . "&color=" . $this->getAvatarColor();
        });
    }

    public function getAvatarBackground()
    {
        return Cache::rememberForever('avatar_background' . $this->id, function () {
            return $this->rand_color(2);
        });
    }

    public function getAvatarColor()
    {
        return Cache::rememberForever('avatar_color' . $this->id, function () {
            return $this->rand_color();
        });
    }

    public function rand_color($opacity = 1)
    {
        return str_pad(dechex(mt_rand(0xFFFFFF / $opacity, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

//    public function friends()
//    {
//        $first = $this->belongsToMany(User::class,'friend_user', 'friend_id', 'user_id');
//        $second = $this->belongsToMany(User::class,'friend_user', 'user_id', 'friend_id');
//        dd($first, $second);
//        return $second->merge($first);
//    }

    public function myFriends()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'friend_id', 'user_id');
    }

    public function friendsOf()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id');
    }

    public function getFriendsAttribute()
    {
        return $this->myFriends->merge($this->friendsOf);
    }
}
