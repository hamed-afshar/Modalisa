<template>
	<div class="modal" v-show="modalValue">
		<modal-header>
			{{ fields.title }}
		</modal-header>
		<slot name="modal-body">
			<modal-body>

			</modal-body>
		</slot>
	</div>
</template>

<script>
    export default {
        name: "general-modal",
        props: {
            title: null,
            fields: Object
        },
        data() {
            return {
                modalValue: false
            }
        },
	    mounted() {
            this.$root.$on('open-modal', () => {
              this.modalValue = true
            });
            this.$root.$on('close-modal', () => {
                this.modalValue = false
            });
        }
    }
</script>

<style scoped>
	.modal-box {
		@apply .bg-white .text-gray-500 .border .rounded-lg .p-2;
	}
</style>
