<template>
	<div class="modal" v-show="modalValue">
		<div class="modal-box">
			<modal-header>
				{{fields.title}}
			</modal-header>
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

	/*.modal-box {*/
	/*	@apply .bg-white .text-gray-500 .border .rounded-lg .p-2;*/
	/*}*/

	.modal {
		position: fixed;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.modal-box {
		background: #FFFFFF;
		box-shadow: 2px 2px 20px 1px;
		overflow-x: auto;
		display: flex;
		flex-direction: column;
	}
</style>
