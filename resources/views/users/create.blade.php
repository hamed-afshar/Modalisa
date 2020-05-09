@include('_site_header')
<div class="container max-w-xs md:max-w-full mx-auto mt-2 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-6">
        <div class="col-start-3 col-span-2">
            <div class="flex flex-wrap justify-center">
                <div
                        class="inline-block bg-pink-800 border border-solid rounded-full px-8 py-4 transform translate-y-1/2 shadow-xl text-white font-bold">
                    {{ __("translate.signup") }}
                </div>
            </div>
            <form method="POST" action="{{ route('users.store')}}" class="bg-white shadow-md rounded px-8 py-8">
                @csrf
                <div class="flex flex-wrap mb-6 mt-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="name"> {{  __('translate.full_name')  }} </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full"
                               type="text" id="name" name="name" placeholder="Alex Morgan" required>
                    </div>
                    @error('name')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="email"> {{  __('translate.email')  }} </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full" type="email" id="email" name="email"
                               placeholder="name@example.com" required>
                    </div>
                    @error('email')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="mobile"> {{  __('translate.mobile')  }}</label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full" type="tel" id="tel" name="tel"
                               placeholder="989123463474" required>
                    </div>
                    @error('tel')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="country"> {{  __('translate.country')  }} </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <select
                                class="input-option w-full"
                                id="country" name="country" required>
                            <option selected disabled>Country</option>
                            <option value="Iran"> {{ __("translate.iran") }}</option>
                            <option value="Turkey"> {{ __("translate.turkey") }}</option>
                        </select>
                    </div>
                    @error('country')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="language"> {{  __('Language')  }} </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <select
                                class="input-option w-full"
                                id="language" name="language" required>
                            <option disabled selected>{{ __("translate.language") }}</option>
                            <option value="Iran"> {{ __("translate.persian") }}</option>
                            <option value="Turkey"> {{ __("translate.turkish") }}</option>
                        </select>
                    </div>
                    @error('language')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>

                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="media"> {{  __('Social Media')  }} </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <select
                                class="input-option w-full"
                                id="communication_media" name="communication_media" required>
                            <option disabled selected>{{ __("translate.communication_media") }}</option>
                            <option value="Iran"> {{ __("translate.whatsapp") }}</option>
                            <option value="Turkey"> {{ __("translate.Telegram") }}</option>
                        </select>
                    </div>
                    @error('communication_media')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="password"> {{  __('translate.password')  }} </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full" type="password" id="password" name="password"
                               placeholder="********" required>
                    </div>
                    @error('password')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label mb-1 py-1" for="password_confirmation"> {{  __('translate.password_confirm')  }} </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full" type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="********" required>
                    </div>
                    @error('password_confirmation')
                    <div class="error"> {{ $message }}</div>
                    @enderror
                </div>
                <div class="flex">
                    <button class="btn-pink uppercase shadow-xl w-full px-1 py-3 mt-1"
                            type="submit">{{ __("translate.signup") }}</button>
                </div>
                <div class="flex justify-center mt-2 w-full">
                    <div class="label pt-2">{{ __("translate.already_have_an_account?") }}</div>
                </div>
                <div class="flex justify-center w-full">
                    <a href="#" class="link p-1">{{ __("translate.login") }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
