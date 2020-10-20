<template>
	<header class="flex items-center justify-between px-4 py-2 bg-white">
		<!-- Left Side -->
		<div class="w-2/3">
      <a v-bind:href="route('welcome')">
        <h1 class="logo-medium"> Modalisa </h1>
      </a>
		</div>
		<!-- Right Side -->
		<div class="w-1/3 flex justify-end">
		</div>
	</header>
</template>

<script>
    export default {
        name: "guest-navbar"
    }
</script>

<style scoped>

</style>
