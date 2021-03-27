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
                @forelse($threads as $thread)
                    <div class="card mt-3">
                        <div class="card-header">
                            <div class="level">
                                <h4 class="flex">
                                    <a href="{{$thread->path()}}">
                                        {{$thread->title}}
                                    </a>
                                </h4>

                                <a href="{{$thread->path()}}">
                                    <strong> {{$thread->replies_count}} {{Str::plural('reply', $thread->replies_count)}}</strong>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div>
                                {{$thread->body}}
                            </div>
                            <hr>
                        </div>
                    </div>
                @empty
                    <h5>
                        There are no relevant results at this time.
                    </h5>
                @endforelse()
            </div>
        </div>
    </div>
@endsection
