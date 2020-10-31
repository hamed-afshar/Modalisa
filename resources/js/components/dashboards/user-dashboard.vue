<template>
  <div>
    <h3 class="text-gray-700 text-3xl font-medium"> Dashboard </h3>
    <div class="mt-4">
      <div class="flex flex-wrap -mx-6">
        <!-- remaining creadit report -->
        <div class="w-full px-6 sm:w-1/2 xl:w-1/4">
          <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="p-3 rounded-full bg-indigo-600 opacity-75">
              <i class="fas fa-check-circle fa-2x text-white"></i>
            </div>
            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">4850</h4>
              <div class="text-gray-500"> TL</div>
            </div>
          </div>
        </div>
        <!-- orders short report -->
        <div class="w-full px-6 sm:w-1/2 xl:w-1/4">
          <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="p-3 rounded-full bg-indigo-600 opacity-75">
              <i class="fas fa-shopping-bag fa-2x text-white"></i>
            </div>
            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">1250</h4>
              <div class="text-gray-500"> Monthly</div>
            </div>
            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">30</h4>
              <div class="text-gray-500"> Daily</div>
            </div>
          </div>
        </div>
        <!-- money short report -->
        <div class="w-full px-6 sm:w-1/2 xl:w-1/4">
          <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="p-3 rounded-full bg-pink-600 opacity-75">
              <i class="fas fa-dollar-sign fa-2x text-white"></i>
            </div>
            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">130,000</h4>
              <div class="text-gray-500"> Monthly</div>
            </div>
            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">750</h4>
              <div class="text-gray-500"> Daily</div>
            </div>
          </div>
        </div>
        <!-- shipping report -->
        <div class="w-full px-6 sm:w-1/2 xl:w-1/4">
          <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white">
            <div class="p-3 rounded-full bg-orange-600 opacity-75">
              <i class="fas fa-shipping-fast fa-2x text-white"></i>
            </div>
            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">250</h4>
              <div class="text-gray-500"> Orders</div>
            </div>
            <div class="mx-5">
              <h4 class="text-2xl font-semibold text-gray-700">25</h4>
              <div class="text-gray-500"> Weight(kg)</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="flex flex-col mt-8">
      <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
          <orders-table-user></orders-table-user>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: "user-dashboard"
}
</script>

<style scoped>

</style>