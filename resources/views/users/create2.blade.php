@include('_site_header')
<div class="container max-w-xs md:max-w-full mx-auto mt-16">
    <div class="grid grid-cols-1 md:grid-cols-6 grid-rows-6">
        <div class="col-start-3 col-span-2 row-start-2 row-end-5">
            <div class="flex flex-wrap justify-center">
                <div class="inline-block bg-pink-600 border border-solid rounded-full px-8 py-4 transform translate-y-1/2 shadow-xl text-white font-bold">Register</div>
            </div>
            <form class="bg-white shadow-md rounded px-8 py-8">
                <div class="flex flex-wrap mb-6 mt-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label" for="name"> Full Name </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full" type="text" id="name" name="name" placeholder="Alex Morgan">
                    </div>
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label" for="email"> Email </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full" type="email" id="email" name="email"
                               placeholder="YourEmail@example.com">
                    </div>
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label" for="mobile"> Mobile </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full" type="tel" id="mobile" name="mobile"
                               placeholder="00989123463474">
                    </div>
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label" for="country"> Country </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <select
                            class="input-option w-full"
                            id="country" name="country">
                            <option selected disabled>Country</option>
                            <option value="Iran"> Iran</option>
                            <option value="Turkey"> Turkey</option>
                            <option value="Ukraine"> Ukraine</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label" for="language"> Language </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <select
                            class="input-option w-full"
                            id="language" name="language">
                            <option disabled selected>Language</option>
                            <option value="Iran"> Persian</option>
                            <option value="Turkey"> Turkish</option>
                            <option value="Ukraine"> Russian</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label" for="media"> Social Media </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <select
                            class="input-option w-full"
                            id="media" name="media">
                            <option disabled selected>Communication Media</option>
                            <option value="Iran"> Whatsapp</option>
                            <option value="Turkey"> Telegram </option>
                        </select>
                    </div>
                </div>
                <div class="flex w-full">
                    <button class="btn-pink uppercase shadow-xl w-full">Signup</button>
                </div>
            </form>
        </div>
    </div>
</div>
