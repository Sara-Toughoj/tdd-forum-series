@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header">
                    <avatar-form :user="{{ $profileUser }}"></avatar-form>
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
