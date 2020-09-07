<template>
    <header class="flex items-center justify-between px-4 py-2 bg-white">
        <!--Left Side -->
        <div class="w-2/3">
            <a v-bind:href="route('')">
                <h1 class="logo-medium"> Modalisa </h1>
            </a>
        </div>
        <div class="w-1/3">

        </div>
    </header>

</template>

<script>
    export default {
        name: "general-navbar"
    }
</script>

<style scoped>

</style>
