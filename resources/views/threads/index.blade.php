@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="col-md-8">
                @include('threads._list')
                <div class="mt-4">
                    {{$threads->links("pagination::bootstrap-4")}}
                </div>
            </div>
            <div class="col-md-4 mt-3">
                <div class="card">
                    <div class="card-header">
                        Trending Threads
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($trending as $thread)
                                <li class="list-group-item">
                                    <a href="{{$thread->path}}">
                                        {{$thread->title}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
@endsection
