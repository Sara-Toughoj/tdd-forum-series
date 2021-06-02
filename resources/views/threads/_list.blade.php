@forelse($threads as $thread)
    <div class="card mt-3">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{$thread->path()}}">
                            @if($thread->hasUpdatesFor(auth()->user()))
                                <strong> {{$thread->title}} </strong>
                            @else
                                {{$thread->title}}
                            @endif
                        </a>
                    </h4>

                    Posted By :
                    <a href="{{route('profile' , $thread->creator)}}">
                        {{$thread->creator->name}}
                    </a>

                </div>
                <a href="{{$thread->path()}}">
                    <strong> {{$thread->replies_count}} {{Str::plural('reply', $thread->replies_count)}}</strong>
                </a>
            </div>
        </div>

        <div class="card-body">
            <div>
                {!! $thread->body !!}
            </div>
        </div>

        <div class="card-footer">
            {{$thread->visits()->count()}} {{Str::plural('visit',$thread->visits()->count())}}
        </div>
    </div>
@empty
    <h5>
        There are no relevant results at this time.
    </h5>
@endforelse()
