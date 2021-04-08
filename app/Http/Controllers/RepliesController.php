<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;


class RepliesController extends Controller
{
    public function store($channel, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $reply = $thread->addReply([
            'body' => request()->body,
            'user_id' => auth()->id(),
        ]);

        if (request()->wantsJson()) {
            return response($reply->load('owner'));
        }

        return back()->with('flash', "Your reply has been left");
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply Delet']);
        }

        return back()->with('flash', 'Reply Deleted successfully');

    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), [
            'body' => 'required'
        ]);

        $reply->update([
            'body' => request()->body
        ]);
    }

    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }
}
