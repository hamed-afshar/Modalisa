<template>
	<header class="flex items-center justify-between px-4 py-2 bg-white">
		<!-- Left Side -->
		<div class="w-2/3">
			<h1 class="logo-medium"> Modalisa </h1>
		</div>
		<!-- Right Side -->
		<div class="w-1/3 flex justify-end">
			<button class="btn-pink mt-2 md:mt-0 md:ml-2 w-full md:w-1/4 lg:w-1/6" v-on:click="logout"> Logout </button>
		</div>
	</header>
</template>

<script>
    export default {
        name: "auth-navbar",
	    date() {
            return {

            }
	    },
	    methods: {
            /*
             * logout user and redirect back to welcome page
             */
            logout() {
                axios.post('/logout')
	                .then(()=>location.href='./');
            }
	    }
    }
</script>

<style scoped>

</style>
