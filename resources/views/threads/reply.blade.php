<reply :attributes="{{$reply}}" inline-template>
    <div class="card mt-3" id="reply-{{$reply->id}}">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a href="{{route('profile',$reply->owner)}}">
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
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control mb-2" v-model="body"></textarea>
                    <button class="btn btn-primary" @click.prevent="update"> Update</button>
                    <button class="btn btn-link" @click="editing=false"> Cancel</button>
                </div>
            </div>
            <div v-else v-html="body"></div>
        </div>

        @can('update' , $reply)
            <div class="card-footer level">
                <button class="btn btn-primary mr-3" @click="editing=true"> Edit</button>
                <form method="post" action="/replies/{{$reply->id}}">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger " type="submit"> Delete</button>
                </form>
            </div>
        @endcan
    </div>
</reply>
