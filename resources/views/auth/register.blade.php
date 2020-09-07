@extends('layouts.app')
@section('content')
    <div id="app">
        <div class="container max-w-xs md:max-w-full mx-auto mt-2 mb-6">
            <div class="flex justify-around py-24">
                <div class="box py-8 px-16">
                    <form method="POST" action="{{ route('register')}}">
                        @csrf
                        <div class="w-full">
                            <div class="w-full text-center font-bold text-xl mb-2">
                                <span class="text-pink-600"> {{ __('translate.register') }} </span>
                            </div>
                            <span class="label py-1"> {{ __('translate.full_name') }}</span>
                            <input class="input-text w-full"
                                   type="text"
                                   id="name"
                                   name="name"
                                   autofocus
                                   required
                                   autocomplete="name"
                                   value="{{old('name')}}"
                                   placeholder="Alex Morgan">
                            @error('name')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <span class="label py-1"> {{ __('translate.email_address') }}</span>
                            <input class="input-text w-full"
                                   type="email"
                                   id="email"
                                   name="email"
                                   required
                                   autocomplete="email"
                                   value="{{ old('email') }}"
                                   placeholder="yourname@example.com">
                            @error('email')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <span class="label py-1"> {{ __('translate.mobile') }}</span>
                            <input class="input-text w-full"
                                   type="text"
                                   id="tel"
                                   name="tel"
                                   required
                                   autocomplete="tel"
                                   value="{{old('tel')}}"
                                   placeholder="905031112233"
                                    maxlength="12">
                            @error('tel')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <span class="label py-1"> {{ __('translate.country') }}</span>
                            <select class="input-option w-full"
                                    id=country"
                                    name="country"
                                    required>
                                <option value="Iran" {{ old('country')=="Iran"?'selected':''}} selected>
                                    {{ __("translate.iran") }}
                                </option>
                                <option value="Turkey" {{ old('country')=="Turkey"?'selected':''}}>
                                    {{ __("translate.turkey") }}
                                </option>
                            </select>
                            @error('country')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <span class="label py-1"> {{ __('translate.language') }}</span>
                            <select class="input-option w-full"
                                    id=language"
                                    name="language"
                                    required>
                                <option value="Persian" {{ old('language')=="Persian"?'selected':''}} selected>
                                    {{ __("translate.persian") }}
                                </option>
                                <option value="Turkish" {{ old('language')=="Turkish"?'selected':''}}>
                                    {{ __("translate.turkish") }}
                                </option>
                            </select>
                            @error('language')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <span class="label py-1"> {{ __('translate.communication_media') }}</span>
                            <select class="input-option w-full"
                                    id="communication_media"
                                    name="communication_media"
                                    required>
                                <option value="WhatsApp" {{ old('communication_media')=="WhatsApp"?'selected':''}} selected>
                                    {{ __("translate.whatsapp") }}
                                </option>
                                <option value="Telegram" {{ old('communication_media')=="Telegram"?'selected':''}}>
                                    {{ __("translate.Telegram") }}
                                </option>
                            </select>
                            @error('communication_media')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <span class="label py-1"> {{ __('translate.password') }}</span>
                            <input class="input-text w-full"
                                   type="password"
                                    id="password"
                                    name="password"
                                    autocomplete="new-password"
                                    required
                                    placeholder="********">
                            @error('password')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <span class="label py-1"> {{ __('translate.password_confirm') }}</span>
                            <input class="input-text w-full"
                                   type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   autocomplete="new-password"
                                   required
                                   placeholder="********">
                            @error('password_confirmation')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                            <button class="w-full btn-pink uppercase shadow-xl px-1 py-2 mt-2" type="submit">
                                {{ __("translate.register") }}
                            </button>
                            <div class="flex mt-2">
                                <div class="w-2/3 label pt-2">
                                    {{ __("translate.already_have_account") }}
                                </div>
                                <div class="w-1/3 link pt-1">
                                    <a href="{{ route("login") }}"> {{ __("translate.login") }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
