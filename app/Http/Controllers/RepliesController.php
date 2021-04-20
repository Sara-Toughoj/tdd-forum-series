<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Models\Reply;
use App\Models\Thread;


class RepliesController extends Controller
{
    public function store($channel, Thread $thread)
    {
        try {
            $this->validateReply();

            $reply = $thread->addReply([
                'body' => request()->body,
                'user_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

        return response($reply->load('owner'));
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
        try {
            $this->validateReply();

            $reply->update([
                'body' => request()->body
            ]);
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

    }

    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    protected function validateReply()
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        resolve(Spam::class)->detect(request('body'));
    }
}
