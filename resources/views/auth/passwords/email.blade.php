@extends('layouts.app')

@section('content')
    <div class="container max-w-full mx-auto">
        <div class="flex justify-around py-24">
            <div class="box py-8 px-6 shadow-2xl">
                <div class="flex flex-wrap">
                    <div class="w-full text-center font-bold text-lg">
                        <span> {{ __('translate.reset_password') }} </span>
                    </div>
                    <div class="w-full pt-4">
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <span class="label pb-1"> {{ __('translate.email_address') }} </span>
                            <input class="input-text w-full"
                                   type="email"
                                   id="email"
                                   name="email"
                                   placeholder="example@domain.com"
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="email"
                                   autofocus>
                            <button class="w-full btn-pink text-sm font-bold mt-2 px-3 py-2" type="submit">
                                {{ __('translate.send_reset_link') }}
                            </button>
                            @error('email')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            @if(session('status'))
                                <div class="success">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
