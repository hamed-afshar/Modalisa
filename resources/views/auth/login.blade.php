@extends('layouts.app')
<div id="app">
    <guest-navbar></guest-navbar>
    <div class="container max-w-full mx-auto">
        <div class="flex justify-around py-24">
            <div class="box py-8 px-16 shadow-2xl">
                <div class="flex flex-wrap">
                    <div class="w-full text-center font-bold text-lg">
                        <span> {{ __('translate.login') }}</span>
                    </div>
                    <div class="w-full pt-4">
                        <div class="flex justify-between"></div>
                        <form method="POST" action="{{ route('login')}}">
                            @csrf
                            <span class="label pb-1">
                            {{__("translate.email_address")}}
                        </span>
                            <input class="w-full input-text"
                                   type="email"
                                   id="email"
                                   name="email"
                                   autofocus
                                   autocomplete="email"
                                   placeholder="your-name@yahoo.com"
                                   value="{{old("email")}}">
                            @error('email')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <span class="label pb-1">
                            {{ __("translate.password") }}
                        </span>
                            <input class="w-full input-text"
                                   type="password"
                                   id="password"
                                   name="password"
                                   autocomplete="new-password"
                                   placeholder="********"
                                   required>
                            @error('password')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <div class="flex justify-between">
                                <div class="w-1/2">
                                    <label class="label" for="remember">
                                        <input class="form-check leading-loose"
                                               type="checkbox"
                                               name="remember"
                                               id="remember"
                                                {{ old('remember') ?'checked':'' }}>
                                        {{ __("translate.remember") }}
                                    </label>
                                </div>
                                <div class="w-1/2">
                                    @if(Route::has('password.request'))
                                        <label class="label">
                                            <a class="link" href="{{ route('password.request') }}">
                                                <span>{{ __("translate.forget_password") }}</span>
                                            </a>
                                        </label>
                                    @endif
                                </div>
                            </div>
                            <button class="w-full btn-pink text-sm font-bold mt-2 px-3 py-2" type="submit">
                                {{ __('translate.login') }}
                            </button>
                            <div class="w-full mt-1">
                                <span>{{ __('translate.dont_have_account') }}</span>
                                <a href=" {{ route('register') }}" class="link">
                                    {{ __('translate.register') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

