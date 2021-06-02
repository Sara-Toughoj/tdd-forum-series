{{-- Editing The Question --}}
<div class="card" v-if="editing">
    <div class="card-header level">
        <input class="form-control" type="text" v-model="form.title">
    </div>

    <div class="card-body">
        <div class="form-group">
            <fancy-editor v-model="form.body" value="form.body"></fancy-editor>
        </div>
    </div>

    @can('update' , $thread)
        <div class="card-footer level">
            <button class="btn btn-primary btn-sm" v-if="!editing" @click="editing = true"> Edit</button>
            <button class="btn btn-primary btn-sm" v-if="editing" @click="update"> Save</button>
            <button class="btn btn-primary btn-sm ml-3" @click="resetForm"> Cancel</button>
            <form method="post" action="{{$thread->path()}}" class="ml-auto">
                @csrf
                @method('delete')
                <button class="btn btn-link" type="submit"> Delete Thread</button>
            </form>
        </div>
    @endcan

</div>


{{-- Viewing The Question --}}
<div class="card" v-else>
    <div class="card-header level">
        <img src="{{$thread->creator->avatar}}" alt="{{$thread->creator->name}}" width="25" height="25" class="mr-2">
        <span class="flex">
            <a href="{{route('profile',$thread->creator)}}"> {{$thread->creator->name}}</a>
            posted:
            <span v-html="thread.title"></span>
        </span>
    </div>

    <div class="card-body">
        <article v-html="thread.body">
        </article>
    </div>

    @can('update' , $thread)
        <div class="card-footer">
            <button class="btn btn-primary btn-sm" @click="editing = true"> Edit</button>
        </div>
    @endcan

</div>
