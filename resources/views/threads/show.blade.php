@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header level">
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
                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                <div class="pt-3">
                    {{$replies->links("pagination::bootstrap-4")}}
                </div>

                @auth
                    <form method="post" action='{{$thread->path() ."/replies"}}'>
                        @csrf
                        <div class="form-group mt-3">
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say ?" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"> Post</button>
                    </form>
                @else
                    <p>Please
                        <a href="{{route('login')}}">
                            sign in
                        </a>
                        to participate
                    </p>
                @endauth

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <article>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="{{route('profile',$thread->creator)}}"> {{$thread->creator->name}} </a>
                            and currently has {{$thread->replies_count}}
                            {{Str::plural('comment' , $thread->replies_count)}}
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
