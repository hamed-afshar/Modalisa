@extends('layouts.app')
@section('content')
    <div id="app">
        @guest
            <guest-navbar></guest-navbar>
        @endguest
        @auth
            <auth-navbar></auth-navbar>
        @endauth
        <div class="container max-w-full mx-auto">
            <div class="flex justify-around py-24">
                <div class="box py-8 px-6 shadow-2xl">
                    <div class="flex flex-wrap">
                        <div class="w-full text-center font-bold text-lg">
                            {{ __('translate.verify_email') }}
                        </div>
                        <div class="w-full text-center font-bold text-pink-600">
                            {{ __('translate.verify_email_sentence') }}
                        </div>
                        <div class="w-full">
                            <div class="flex justify-center">
                                <form class="w-1/3" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                    <button class="w-full btn-pink text-sm font-bold mt-2 px-3 py-2" type="submit">
                                        {{ __('translate.verify_again') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex justify-center">
                                @if(session('resent'))
                                    <div class="success">
                                        {{ __('translate.verify_again') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        {{--        <div class="container">--}}
        {{--            <div class="row justify-content-center">--}}
        {{--                <div class="col-md-8">--}}
        {{--                    <div class="card">--}}
        {{--                        <div class="card-header">{{ __('Verify Your Email Address') }}</div>--}}

        {{--                        <div class="card-body">--}}
        {{--                            @if (session('resent'))--}}
        {{--                                <div class="alert alert-success" role="alert">--}}
        {{--                                    {{ __('A fresh verification link has been sent to your email address.') }}--}}
        {{--                                </div>--}}
        {{--                            @endif--}}

        {{--                            {{ __('Before proceeding, please check your email for a verification link.') }}--}}
        {{--                            {{ __('If you did not receive the email') }},--}}
        {{--                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">--}}
        {{--                                @csrf--}}
        {{--                                <button type="submit"--}}
        {{--                                        class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>--}}
        {{--                                .--}}
        {{--                            </form>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--    </div>--}}
    </div>
@endsection
