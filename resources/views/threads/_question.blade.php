{{-- Editing The Question --}}
<div class="card" v-if="editing">
    <div class="card-header level">
        <input class="form-control" type="text" value="{{$thread->title}}">
    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" rows="10">{{$thread->body}}</textarea>
        </div>
    </div>

    <div class="card-footer level">
        <button class="btn btn-primary btn-sm" v-if="!editing" @click="editing = true"> Edit</button>
        <button class="btn btn-primary btn-sm" v-if="editing" @click="update"> Save </button>
        <button class="btn btn-primary btn-sm ml-3" @click="editing = false"> Cancel</button>
        @can('update' , $thread)
            <form method="post" action="{{$thread->path()}}" class="ml-auto">
                @csrf
                @method('delete')
                <button class="btn btn-link" type="submit"> Delete Thread</button>
            </form>
        @endcan
    </div>

</div>


{{-- Viewing The Question --}}
<div class="card" v-else>
    <div class="card-header level">
        <img src="{{$thread->creator->avatar}}" alt="{{$thread->creator->name}}" width="25" height="25" class="mr-2">
        <span class="flex">
            <a href="{{route('profile',$thread->creator)}}"> {{$thread->creator->name}}</a>
            posted:
            {{$thread->title}}
        </span>
    </div>

    <div class="card-body">
        <article>
            {{$thread->body}}
        </article>
    </div>

    <div class="card-footer">
        <button class="btn btn-primary btn-sm" @click="editing = true"> Edit</button>
    </div>
</div>
