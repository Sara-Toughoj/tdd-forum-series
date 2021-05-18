<?php

namespace App\Models;

use App\Events\ThreadReceivedNewReply;
use App\RecordsActivity;
use App\Visits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Thread extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'body', 'title', 'user_id',
        'channel_id', 'replies_count',
        'slug', 'best_reply_id', 'locked'
    ];

    protected $with = ['channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean',
    ];

    //-------------------------------------  Boot  -------------------------------------

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('creator', function ($builder) {
            return $builder->with('creator');
        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        static::created(function ($thread) {
            $thread->update([
                'slug' => $thread->title
            ]);
        });
    }

    //-------------------------------------  Tools  -------------------------------------

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($this, $reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($user_id = null)
    {
        $this->subscriptions()->create([
            'user_id' => $user_id ?? auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($user_id = null)
    {
        $this->subscriptions()
            ->where('user_id', $user_id ?? auth()->id())
            ->delete();
    }

    public function hasUpdatesFor($user = null)
    {
        $user = $user ?? auth()->user();

        if (!$user) return false;

        $key = $user->visitedThreadCacheKey($this);
        return $this->updated_at > cache($key);
    }

    public function visits()
    {
        return new Visits($this);
    }

    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value);

        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-" . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function setBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

    public function lock()
    {
        $this->update(['locked' => true]);
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

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    //-------------------------------------  Accessors and Mutators  -------------------------------------
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

}
