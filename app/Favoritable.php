<?php


namespace App;


use App\Models\Favorite;
use App\Models\Reply;

trait Favoritable
{

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
        $this->favorites()->firstOrcreate([
            'user_id' => auth()->id()
        ]);
    }
}
