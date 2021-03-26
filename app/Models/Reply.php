<?php

namespace App\Models;

use App\Favoritable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory, Favoritable;

    protected $fillable = ['body', 'user_id', 'thread_id'];

    protected $with = ['owner', 'favorites'];

    //-------------------------- Relationship --------------------------
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
