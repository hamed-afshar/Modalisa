@extends('layouts.app')

@section('content')
    <div class="container max-w-full mx-auto">
        <div class="flex justify-around py-24">
            <div class="box py-8 px-6 shadow-2xl">
                <div class="flex flex-wrap">
                    <div class="w-full text-center font-bold tex-lg">
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
{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Reset Password') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    <form method="POST" action="{{ route('password.update') }}">--}}
{{--                        @csrf--}}

{{--                        <input type="hidden" name="token" value="{{ $token }}">--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>--}}

{{--                                @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">--}}

{{--                                @error('password')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row mb-0">--}}
{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                                    {{ __('Reset Password') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
@endsection
