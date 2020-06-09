<template>
	<div class="modal" v-show="modalValue">
		<div class="modal-header">
			<div class="flex flex-row">
				<div class="w-1/2">
					<h1 class="font-bold">
						{{ fields.title }}
					</h1>
				</div>
				<div class="w-1/2 flex pt-1 justify-end">
					<i class="fas fa-times cursor-pointer"></i>
				</div>
			</div>
		</div>
		<div class="modal-box">
			<div class="grid grid-cols-6 mt-3">
				<div class="col-start-3 col-end-5">
					<div v-for="item in fields.attr.inputs">
						<div class="flex mt-2">
							<input class="input-text w-full" type="text" id="field" name="roleName"
							       v-bind:placeholder=" item.name " autofocus>
						</div>
					</div>
					<div class="flex w-full mt-4">
						<button class="btn-pink w-full mb-2"> save</button>
					</div>
				</div>
			</div>
		</div>
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
	.modal-header {
		@apply .inline-block .border .rounded .shadow-2xl .bg-purple-700 .px-3 .py-2 .w-full .text-white .font-bold;
	}

	.modal-box {
		@apply .bg-white .text-gray-500 .border .rounded-lg .p-2;
	}
</style>
