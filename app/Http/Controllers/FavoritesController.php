<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Reply;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function store(Reply $reply)
    {
        $reply->favorite();
        return redirect()->back();
    }
}
