<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function store(Channel $channel, Thread $thread)
    {
        $thread->subscribe();
    }

    public function delete(Channel $channel, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
