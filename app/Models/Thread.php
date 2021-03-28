<?php

namespace App\Models;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = ['body', 'title', 'user_id', 'channel_id'];

    protected $withCount = ['replies'];

    protected $with = ['channel'];


    //-------------------------------------  Global Scopes  -------------------------------------

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('creator', function ($builder) {
            return $builder->with('creator');
        });

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });
    }

    //-------------------------------------  Tools  -------------------------------------
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }


    //-------------------------------------  Relationships  -------------------------------------

    public function replies()
    {
        return $this->hasMany(Reply::class)
            ->withCount('favorites')
            ->with('owner');

    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }


}
