@extends('layouts.app')

@section('header')
    <link href="{{ asset('css/vendor/tribute.css') }}" rel="stylesheet">
@endsection

@section('content')
    <thread-view :initial-replies-count="{{$thread->replies_count}}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header level">
                            <img src="{{$thread->creator->avatar}}" alt="{{$thread->creator->name}}" width="25" height="25" class="mr-2">
                            <span class="flex">
                            <a href="{{route('profile',$thread->creator)}}"> {{$thread->creator->name}}</a>
                            posted:
                            {{$thread->title}}
                        </span>
                            @can('update' , $thread)
                                <form method="post" action="{{$thread->path()}}">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-link" type="submit"> Delete Thread</button>
                                </form>
                            @endcan
                        </div>

                        <div class="card-body">
                            <article>
                                {{$thread->body}}
                            </article>
                        </div>
                    </div>

                    <replies @removed="repliesCount--" @added="repliesCount++"></replies>

                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <article>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="{{route('profile',$thread->creator)}}"> {{$thread->creator->name}} </a>
                                and currently has <span v-text="repliesCount"> </span>
                                {{Str::plural('comment' , $thread->replies_count)}}
                            </article>
                            @auth
                                <subscribe-button is-subscribed="{{$thread->isSubscribedTo}}"></subscribe-button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
