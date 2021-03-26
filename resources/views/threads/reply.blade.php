<div class="card mt-3">
    <div class="card-header">
        <div class="level">
            <h5 class="flex">
                <a href="#">
                    {{$reply->owner->name}}
                </a>
                said
                {{$reply->created_at->diffForHumans()}}
            </h5>

            <form method="POST" action="/replies/{{$reply->id}}/favorites">
                @csrf
                <button type="submit" class="btn btn-primary" {{$reply->isFavorited()?'disabled':''}}>
                    {{$reply->favorites_count}} {{Str::plural('favorite',$reply->favorites_count)}}
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <article> {{$reply->body}} </article>
    </div>
</div>
