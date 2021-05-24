<?php

namespace App\Http\Controllers;

use App\Filters\ThreadFilters;
use App\Models\Channel;
use App\Models\Thread;
use App\Rules\Recaptcha;
use App\Rules\SpamFree;
use App\Trending;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class ThreadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @param Trending $trending
     * @return Application|Factory|View
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($filters, $channel);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Recaptcha $recaptcha
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(Recaptcha $recaptcha)
    {

        request()->validate([
            'title' => ['required', new SpamFree()],
            'body' => ['required', new SpamFree()],
            'channel_id' => 'required|exists:' . (new Channel())->getTable() . ',id',
            'g-recaptcha-response' => ['required', $recaptcha]
        ]);

        $thread = Thread::create([
            'title' => request('title'),
            'body' => request('body'),
            'user_id' => auth()->id(),
            'channel_id' => request()->channel_id,
        ]);

        if (request()->wantsJson()) {
            return response($thread, 201);
        }

        return redirect($thread->path())
            ->with('flash', "Your thread has been published");
    }

    /**
     * Display the specified resource.
     *
     * @param $channel
     * @param Thread $thread
     * @param Trending $trending
     * @return Application|Factory|View
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $thread->visits()->record();

        $trending->push($thread);

        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(25)
        ]);
    }


    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->update(request()->validate([
            'title' => ['required', new SpamFree()],
            'body' => ['required', new SpamFree()],
        ]));
        return $thread;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Thread $thread
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();
        if (request()->wantsJson()) {
            return response([], 204);
        }
        return redirect('/threads');
    }

    /**
     * @param ThreadFilters $filters
     * @param Channel $channel
     * @return Collection
     */
    protected function getThreads(ThreadFilters $filters, Channel $channel)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads = $channel->threads()->latest();
        }

        return $threads->paginate(25);
    }
}
