<template>
    <div class="flex">
        <!-- Backdrop -->
        <div v-bind:class="isOpen ? 'block' : 'hidden'"
             v-on:click="isOpen = false"
             class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden">
        </div>
        <div v-bind:class="isOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
             class="fixed z-30 inset-y-0 left0 w-64
                transition duration-300 transform
                transform bg-gray-900 overflow-y-auto
                lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex items-center justify-center mt-8">
                <div class="flex items-center">
                <span class="text-white text-2xl mx-2 font-semibold">
                    {{ $t('translate.dashboard') }}
                </span>
                </div>
            </div>
            <nav class="mt-10">
                <div class="flex items-center mt-4 py-2 px-6">
                    <i class="fas fa-th-list text-white"></i>
                    <span class="text-white px-6 text-lg">
                        Orders Table
                    </span>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
export default {
    name: "users-sidebar",
    data() {
        return {
            isOpen: false,
        }
    }
}
</script>

<style scoped>

</style>
