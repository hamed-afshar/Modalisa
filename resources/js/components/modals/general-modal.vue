<template>
	<div>
		<modal name="general-modal" id="general-modal" height="auto">
			<modal-header>
				{{ title }}
			</modal-header>
			<modal-body>
				<modal-content>
				</modal-content>
			</modal-body>
			<div v-for="field in fields" :key="field">
				{{ field }}
			</div>
		</modal>

	</div>
</template>

<script>
    export default {
        name: "general-modal",
	    props: {
			title: null,
		    fields: Array,
	    }
    }
</script>

<style scoped>

</style>