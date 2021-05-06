@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="col-md-8">
                @include('threads._list')
                <div class="mt-4">
                    {{$threads->links("pagination::bootstrap-4")}}
                </div>
            </div>
        </div>
    </div>
@endsection
