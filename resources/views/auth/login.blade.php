@include('_site_header')
<div class="container max-w-xs md:max-w-full mx-auto mt-2 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-6">
        <div class="col-start-3 col-span-2">
            <div class="flex flex-wrap justify-center">
                <div
                        class="inline-block bg-pink-800 border border-solid rounded-full px-8 py-4 transform translate-y-1/2 shadow-xl text-white font-bold">
                    {{ __('translate.signin') }}
                </div>
            </div>
            <form method="POST" action="{{ route('login')}}" class="bg-white shadow-md rounded px-8 py-8">
                @csrf
                <div class="flex flex-wrap mb-6 mt-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="email"> {{  __('translate.full_name')  }} </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full"
                               type="email" id="email" name="email" value="{{ old("email") }}" autofocus autocomplete="email" placeholder="Alex@domain.com" required>
                    </div>
                    @error('name')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="password"> {{  __('translate.password')  }} </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full" type="password" id="password" name="password"
                               autocomplete="new-password" placeholder="********" required>
                    </div>
                    @error('password')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="flex flex-wrap mb-6 justify-center">
                    <div class="flex w-full lg:w-1/3 justify-center lg:justify-end">
                        <label class="label" for="remember">
                            {{ __("translate.remember") }}
                        </label>
                    </div>
                    <div class="flex w-full lg:w-1/3 pl-2 justify-center">
                        <input type="checkbox" class="form-check" name="remember"
                               id="remember" {{ old('remember') ? 'checked' : '' }} >
                    </div>
                </div>
                <div class="flex">
                    <button class="btn-pink uppercase shadow-xl w-full px-1 py-3 mt-1"
                            type="submit">{{ __("translate.login") }}</button>
                </div>
                <div class="flex justify-center mt-2 w-full">
                    @if(Route::has('password.request'))
                        <a class="" href="{{ route('password.request') }}">
                            {{ __("translate.forget_password") }}
                        </a>
                    @endif
                </div>
                <div class="flex justify-center w-full">
                    <a href="{{ route('register') }}" class="link p-1">{{ __("translate.signup") }}</a>
                </div>

            </form>
        </div>
    </div>
</div>
