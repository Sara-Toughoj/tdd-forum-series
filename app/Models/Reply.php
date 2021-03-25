<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'thread_id'];

    //-------------------------- Relationship --------------------------
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
        return $this->MorphMany(Favorite::class, 'favorited');
    }

    //-------------------------- Tools --------------------------
    public function favorite()
    {
        $this->favorites()->firstOrcreate([
            'user_id' => auth()->id()
        ]);
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }
}
