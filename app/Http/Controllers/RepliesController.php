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
        $reply = $form->persist($thread);

        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);
        $names = $matches[1];

        foreach ($names as $name) {
            $user = User::where('name', 'like', '%' . $name . '%')->first();
            if ($user) {
                $user->notify(new YouWereMentioned($reply));
            }
        }

        return response($reply);
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
            $validator = validator(request()->all(), [
                'body' => ['required', new SpamFree()],
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

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
}
