@extends('layouts.app')
@section('content')
    <div id="app">
        <general-navbar></general-navbar>
        <div class="container max-w-full mx-auto">
            <div class="flex justify-around py-24">
                <div class="box py-8 px-6 shadow-2xl">
                    <div class="flex flex-wrap">
                        <div class="w-full text-center font-bold text-lg">
                            <span> {{ __('translate.reset_password') }} </span>
                        </div>
                        <div class="w-full pt-4">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <span class="label pb-1"> {{ __('translate.email_address') }} </span>
                                <input class="input-text w-full"
                                       id="email"
                                       name="email"
                                       type="email"
                                       autofocus
                                       autocomplete="email"
                                       value="{{ $email ?? old('email') }}">
                                @error('email')
                                <div class="error">
                                    {{ $message }}
                                </div>
                                @enderror
                                <span class="label pb-1"> {{ __('translate.password') }} </span>
                                <input class="input-text w-full"
                                       id="password"
                                       name="password"
                                       type="password"
                                       required
                                       autocomplete="new-password">
                                @error('password')
                                <div class="error">
                                    {{ $message }}
                                </div>
                                @enderror
                                <span class="label pb-1"> {{ __('translate.password_confirm') }} </span>
                                <input class="input-text w-full"
                                       id="password-confirm"
                                       name="password_confirmation"
                                       type="password"
                                       required
                                       autocomplete="new-password">
                                <button class="w-full btn-pink text-sm font-bold mt-2 px-3 py-2" type="submit">
                                    {{ __('translate.reset_password') }}
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
