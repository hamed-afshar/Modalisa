@include('_site_header')
<div class="container mx-auto mt-16">
    <div class="grid grid-cols-6 grid-rows-6">
        <div class="col-start-3 col-span-2 row-start-2 row-end-5">
            <form class="bg-white shadow-md rounded px-8 py-8">
                <div class="flex flex-wrap mb-6">
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
                        <input class="input-text w-full" type="email" id="email" name="email" placeholder="YourEmail@example.com">
                    </div>
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label" for="mobile"> Mobile </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <input class="input-text w-full" type="number" id="mobile" name="mobile" placeholder="00989123463474">
                    </div>
                </div>
                <div class="flex flex-wrap mb-6">
                    <div class="w-full lg:w-1/3">
                        <label class="label" for="country"> Country </label>
                    </div>
                    <div class="w-full lg:w-2/3">
                        <select class="appearance-none border border-gray-200 rounded bg-gray-300 block focus:outline-none focus:bg-white focus:border-pink-600 leading-tight px-2 py-1 text-gray-700 w-full" id="country" name="country" >Country
                            <option disabled>Select country</option>
                            <option value="Iran"> Iran</option>
                            <option value="Turkey"> Turkey</option>
                            <option value="Ukraine"> Ukraine</option>
                        </select>
                    </div>
                </div>
                <button class="btn-blue">hello</button>
            </form>

        </div>
    </div>
</div>
