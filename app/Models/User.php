<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email',
    ];

    protected $appends = ['avatar'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const ADMINS = [
        'JohnDoe',
        'JaneDoe',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    //------------------------------- Relationships -------------------------------

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }


    //------------------------------- Tools -------------------------------
    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits%s", $this->id, $thread->id);

    }

    public function read($thread)
    {
        $key = Auth()->user()->visitedThreadCacheKey($thread);
        cache()->forever($key, Carbon::now());
    }

    public function getAvatarAttribute()
    {
        return asset($this->avatar_path ? 'storage/' . $this->avatar_path : 'storage/avatars/default.png');
    }

    public function isAdmin()
    {
        return in_array($this->name, self::ADMINS);
    }
}
