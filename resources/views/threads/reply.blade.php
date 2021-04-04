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
                @auth
                    <favorite :reply="{{ $reply }}"></favorite>
                @endauth
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control mb-2" v-model="body"></textarea>
                    <button class="btn btn-primary" @click="update"> Update</button>
                    <button class="btn btn-link" @click="editing=false"> Cancel</button>
                </div>
            </div>
            <div v-else v-html="body"></div>
        </div>

        @can('update' , $reply)
            <div class="card-footer level">
                <button class="btn btn-primary mr-3" @click="editing=true"> Edit</button>
                <button class="btn btn-danger " @click="destroy"> Delete</button>
            </div>
        @endcan
    </div>
</reply>
