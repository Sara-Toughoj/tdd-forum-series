@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <h1>
                        {{$profileUser->name}}
                    </h1>
                </div>

                @foreach($activities as $activities_date =>$activity_group)
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
                @endforeach
            </div>
        </div>
    </div>


@endsection
