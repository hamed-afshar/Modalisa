<template>
  <div class="flex">
    <!-- Backdrop -->
    <div v-bind:class="isOpen ? 'block' : 'hidden'"
         v-on:click="isOpen = false"
         class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden">
    </div>
    <div v-bind:class="isOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
         class="fixed z-30 inset-y-0 left-0 w-64
                transition duration-300 transform
                bg-gray-900 overflow-y-auto
                lg:translate-x-0 lg:static lg:inset-0">
      <div class="flex flex-col items-center justify-center mt-8">
        <div class="text-white">
                    <span class="text-white text-2xl mx-2 font-semibold">
                        {{ $t('translate.dashboard') }}
                    </span>
        </div>
        <div class="w-full">
          <hr class="border-pink-600">
        </div>
        <div class="text-white mt-4">
                    <span class="text-white mx-2">
                        Gold Subscription
                    </span>
        </div>mai
      </div>
      <nav class="mt-10">
        <div class="flex items-center sidebar-menu-item">
          <a v-on:click="passLink('orders')">
            <i class="fas fa-th-list sidebar-menu-icon"></i>
            <span class="sidebar-menu-text"> {{ $t('translate.orders_table') }} </span>
          </a>
        </div>
        <div class="flex items-center sidebar-menu-item">
          <a v-on:click="passLink('wallet')">
            <i class="fas fa-wallet sidebar-menu-icon"></i>
            <span class="sidebar-menu-text"> {{ $t('translate.wallet') }} </span>
          </a>
        </div>
        <div class="flex items-center sidebar-menu-item">
          <a v-on:click="passLink('customers')">
            <i class="fa fa-users sidebar-menu-icon"></i>
            <span class="sidebar-menu-text"> {{ $tc('translate.customer', 1) }} </span>
          </a>
        </div>
        <div class="flex items-center sidebar-menu-item">
          <a v-on:click="passLink('reports')">
            <i class="fas fa-book-reader sidebar-menu-icon"></i>
            <span class="sidebar-menu-text"> {{ $tc('translate.report', 1) }} </span>
          </a>
        </div>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  name: "users-sidebar",
  setup() {

  },
  data() {
    return {
      isOpen: false,
    }
  },
  methods: {
    passLink(link) {
      switch (link) {
        case 'orders':
          Event.$emit('orders');
          break;
        case 'customers':
          Event.$emit('customers');
          break;
        case 'wallet':
          Event.$emit('wallet');
          break;
        case 'reports':
          Event.$emit('reports');
          break;

      }
    }
  }
}
</script>

<style scoped>
.sidebar-menu-item {
  @apply .mt-4 .py-2 .px-6 .cursor-pointer
}

.sidebar-menu-item:hover {
  @apply .bg-gray-600 .text-gray-100
}

.sidebar-menu-text {
  @apply .text-white .px-6 .text-base
}

.sidebar-menu-icon {
  @apply .text-pink-600
}


</style>
