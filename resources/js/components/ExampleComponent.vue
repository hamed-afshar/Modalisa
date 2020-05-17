<template>
	<div name="modalisa-msg">
		<div class="text-red-600" v-show="isVisible"><h1> {{ title }}</h1></div>
		<div class="text-green-600"><h1> {{ body }} </h1></div>
		<button class="btn-pink" v-on:click="hideTitle"> Hide</button>
	</div>
</template>

<script>
    export default {
        name: "ExampleComponent",
        props: ["title", "body"],
        methods: {
            hideTitle() {
				this.isVisible=false
            },
        },
        data() {
            return {
                isVisible: true
            }
        }
    }
</script>
