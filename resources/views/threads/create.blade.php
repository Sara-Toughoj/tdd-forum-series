@extends('layouts.app')
@section('header')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a New Thread</div>

                    <div class="card-body">

                        <form method="post" action="/threads">
                            @csrf
                            <div class="form-group">
                                <label for="channel_id"> Choose a channel : </label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="" disabled selected> choose a channel</option>
                                    @foreach($channels as $channel)
                                        <option value="{{$channel->id}}" {{old('channel_id') == $channel->id ? 'selected':''}}>
                                            {{$channel->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title"> Title : </label>
                                <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" required>
                            </div>

                            <div class="form-group">
                                <label for="body"> Body : </label>
                                <fancy-editor name="body"></fancy-editor>
                            </div>

                            <div class="g-recaptcha mb-3" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>


                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary"> Publish</button>
                            </div>

                        </form>
                        @if(count($errors))
                            <ul class="alert alert-danger mt-3">
                                @foreach($errors->all() as $error)
                                    <li>
                                        {{$error}}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
