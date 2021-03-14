@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#">
                            {{$thread->creator->name}}
                        </a>
                        posted:
                        {{$thread->title}}
                    </div>

                    <div class="card-body">
                        <article>
                            {{$thread->body}}
                        </article>
                    </div>
                </div>
            </div>
        </div>

        @if($thread->replies)
            <div class="row justify-content-center">
                <div class="col-md-8">
                    @foreach($thread->replies as $reply)
                        @include('threads.reply')
                    @endforeach
                </div>
            </div>
            @auth
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <form method="post" action='{{$thread->path() ."/replies"}}'>
                            @csrf
                            <div class="form-group mt-3">
                                <textarea name="body" id="body" class="form-control" placeholder="Have something to say ?" rows="5"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"> Post</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="row justify-content-center mt-3">
                    <div class="col-md-8">
                        <p>Please
                            <a href="{{route('login')}}">
                                sign in
                            </a>
                            to participate
                        </p>
                    </div>
                </div>
            @endauth
        @endif
    </div>
@endsection
