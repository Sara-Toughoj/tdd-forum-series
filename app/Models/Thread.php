<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    //-------------------------------------  Tools  -------------------------------------
    public function path()
    {
        return '/threads/' . $this->id;
    }

    //-------------------------------------  Tools  -------------------------------------

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
