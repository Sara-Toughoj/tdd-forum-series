<?php


namespace App;


use App\Models\Favorite;
use App\Models\Reply;

trait Favoritable
{

    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }
    
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function favorites()
    {
        return $this->MorphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = [
            'user_id' => auth()->id()
        ];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = [
            'user_id' => auth()->id()
        ];

        $this->favorites()->where($attributes)->get()->each->delete();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }
}
