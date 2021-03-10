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
        @endif
    </div>
@endsection
