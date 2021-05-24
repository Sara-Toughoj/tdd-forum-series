@extends('layouts.app')

@section('header')
    <link href="{{ asset('css/vendor/tribute.css') }}" rel="stylesheet">
@endsection

@section('content')
    <thread-view :data-replies-count="{{$thread->replies_count}}" data-locked="{{$thread->locked}}" data-slug="{{$thread->slug}}" :thread="{{$thread}}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8" v-cloak>
                    @include('threads._question')

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
                                <div class="mt-4">
                                    <subscribe-button is-subscribed="{{$thread->isSubscribedTo}}"></subscribe-button>
                                    <button class="btn btn-danger"
                                            v-if="authorize('isAdmin')"
                                            @click="toggleLock"
                                            v-text="locked ? 'Unlock' : 'Lock'">
                                        Lock
                                    </button>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
