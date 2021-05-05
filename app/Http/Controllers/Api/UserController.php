<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $search = request('name');

        return User::where('name', 'like', "$search%")
            ->take(5)
            ->pluck('name')
            ->map(function ($name) {
                return ['value' => $name];
            });
    }
}
