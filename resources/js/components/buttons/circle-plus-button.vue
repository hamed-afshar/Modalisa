<template>
	<div class="btn-circle-pink">
		<i class="fas fa-plus"></i>
	</div>
</template>

<script>
    export default {
        name: "circle-plus-button"
    }
</script>

<style scoped>
	.btn-circle {
		@apply .inline-block .flex .text-white .h-12 .w-12 .border .rounded-full .justify-center .items-center .cursor-pointer;
	}

	.btn-circle-pink {
		@apply .btn-circle .bg-pink-600
	}

	.btn-circle-pink:hover {
		@apply .btn-circle .bg-pink-800
	}
</style>