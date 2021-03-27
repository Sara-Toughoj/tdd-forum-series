@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>
                        {{$profileUser->name}}
                        <small> Since {{$profileUser->created_at->diffForHumans()}} </small>
                    </h1>
                </div>

                @foreach($threads as $thread)
                    <div class="card mt-3">
                        <div class="card-header">
                            <div class="level">
                        <span class="flex">
                            <a href="{{route('profile',$thread->creator)}}"> {{$thread->creator->name}}</a>
                            posted:
                            {{$thread->title}}
                        </span>
                                <span>
                            {{$thread->created_at->diffForHumans()}}
                        </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <article>
                                {{$thread->body}}
                            </article>
                        </div>
                    </div>
                @endforeach
                <div class="mt-3">
                    {{$threads->links("pagination::bootstrap-4")}}
                </div>

            </div>
        </div>
    </div>


@endsection
