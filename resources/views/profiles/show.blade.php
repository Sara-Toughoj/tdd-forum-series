@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <h1>
                        {{$profileUser->name}}
                    </h1>
                    @can('update' , $profileUser)
                        <form method="post" action="{{route('avatar',$profileUser)}}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="avatar">
                            <button type="submit" class="btn btn-primary"> Add Avatar</button>
                        </form>
                    @endcan
                    <img src="{{$profileUser->avatar()}}" width="50" height="50">
                    <hr>
                </div>

                @forelse($activities as $activities_date =>$activity_group)
                    <h4 class="mt-4">
                        {{$activities_date}}
                    </h4>
                    <hr/>
                    @foreach($activity_group as $activity)
                        @if(view()->exists("profiles.activities.$activity->type"))
                            @include("profiles.activities.$activity->type")
                        @endif
                    @endforeach
                    <div class="mt-3">
                    </div>
                @empty
                    <h5> No recent activity</h5>
                @endforelse
            </div>
        </div>
    </div>


@endsection
