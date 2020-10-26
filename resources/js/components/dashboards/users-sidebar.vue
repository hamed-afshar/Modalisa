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
                <div class="text-white">
                    <span class="text-white mx-2">
                        Gold Subscription
                    </span>
                </div>
            </div>
            <nav class="mt-10">
                <div class="flex items-center mt-4 py-2 px-6 cursor-pointer menu-item">
                    <i class="fas fa-th-list text-white"></i>
                    <span class="text-white px-6 text-lg"> Orders Table </span>
                </div>
                <div class="flex items-center mt-4 py-2 px-6"
                     v-on:mouseover="onMouseOver"
                     v-on:mouseleave="onMouseLeave"
                     v-bind:class="{ 'active-item' : isActive}">
                    <i class="fa fa-users text-white"></i>
                    <span class="text-white px-6 text-lg"> Customers </span>
                </div>
                <div class="flex items-center mt-4 py-2 px-6"
                     v-on:mouseover="onMouseOver"
                     v-on:mouseleave="onMouseLeave"
                     v-bind:class="[isActive ? activeClass : inactiveClass]">
                    <i class="fas fa-file-invoice-dollar text-white"></i>
                    <span class="text-white px-6 text-lg"> Transfer </span>
                </div>
                <div class="flex items-center mt-4 py-2 px-6"
                     v-on:mouseover="onMouseOver"
                     v-on:mouseleave="onMouseLeave"
                     v-bind:class="[isActive ? activeClass : inactiveClass]">
                    <i class="fas fa-bookmark text-white"></i>
                    <span class="text-white px-6 text-lg"> Refund & Exchange </span>
                </div>
                <div class="flex items-center mt-4 py-2 px-6"
                     v-on:mouseover="onMouseOver"
                     v-on:mouseleave="onMouseLeave"
                     v-bind:class="[isActive ? activeClass : inactiveClass]">
                    <i class="fas fa-tag text-white"></i>
                    <span class="text-white px-6 text-lg"> Available </span>
                </div>
                <div class="flex items-center mt-4 py-2 px-6"
                     v-on:mouseover="onMouseOver"
                     v-on:mouseleave="onMouseLeave"
                     v-bind:class="[isActive ? activeClass : inactiveClass]">
                    <i class="fas fa-book-reader text-white"></i>
                    <span class="text-white px-6 text-lg"> Reports </span>
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
            isActive: false,
            isOpen: false,

            activeClass: ['bg-gray-600', 'bg-opacity-25', 'text-gray-100', 'border-gray-100'],
            inactiveClass: ['border-gray-900', 'text-gray-500', 'hover:bg-gray-600', 'hover:bg-opacity-25', 'hover:text-gray-100']
        }
    },
    methods: {
        onMouseOver() {
            this.isActive = !this.isActive;
        },
        onMouseLeave() {
            this.isActive = !this.isActive;
        }
    }
}
</script>

<style scoped>
.active-item {
    @apply .border-gray-600 .text-gray-100 .border-gray-100
}
.inactive-item {
    @apply .border-gray-900 .text-gray-500
}

.inactive-item:hover {
    @apply .bg-gray-600 .text-gray-100
}


</style>
