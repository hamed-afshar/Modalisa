@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">{{ __("translate.pending_for_confirmation_title") }}</h1>
            <p class="lead"> {{ __("translate.pending_for_confirmation_text1") }}</p>
            <hr class="my-4">
            <p> {{ __("translate.pending_for_confirmation_text2") }} </p>
        </div>

    </div>
    @include('layouts.footer')

@endsection


