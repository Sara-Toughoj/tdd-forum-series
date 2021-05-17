<?php

namespace App\Http\Controllers;

use App\Http\Forms\CreatePostForm;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\YouWereMentioned;
use App\Rules\SpamFree;
use Illuminate\Support\Facades\Gate;


class RepliesController extends Controller
{
    public function store($channel, Thread $thread, CreatePostForm $form)
    {
        return $form->persist($thread);
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
            'body' => ['required', new SpamFree()],
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
