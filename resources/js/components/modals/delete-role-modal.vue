<template>
	<modal name="delete-role-modal" id="delete-role-modal" height="auto"
	       @before-open="beforeOpen">
		<div class="modal-box">
			<div class="modal-header">
				<div class="flex flex-row">
					<div class="w-1/2">
						<h1 class="font-bold"> {{ fields.title }} </h1>
					</div>
					<div class="w-1/2 flex pt-1 justify-end">
						<i class="fas fa-times cursor-pointer" v-on:click="$modal.hide('delete-role-modal')"></i>
					</div>
				</div>
			</div>
			<div class="grid grid-cols-6 mt-3">
				<div class="col-start-3 col-end-5">
					<div>
						<div class="flex mt-2">
							<input class="input-text w-full" type="text" v-model="name" id="name" name="name"
							       placeholder="Name" disabled autofocus>
						</div>
					</div>
					<div class="flex w-full mt-4">
						<button class="btn-pink w-full mb-2" v-on:click="del"> {{ $t('translate.delete') }}</button>
					</div>
				</div>
			</div>
		</div>
	</modal>
</template>

<script>
    export default {
        name: "delete-role-modal",
        props: {
            fields: Object,
        },
        data() {
            return {
                id: null,
                name: null
            }
        },
        methods: {
            //delete function
            del() {
                axios.delete('/roles/' + this.id).then(function () {
                    Event.$emit('delete');
                })
            },
            //function to be executed before opening modal
            beforeOpen(event) {
                //get parameters from roles-table modal
                this.id = event.params.id;
                this.name = event.params.name;
            }
        },
    }
</script>

<style scoped>
	.modal-box {
		@apply .bg-white .text-gray-500 .border .rounded-lg .p-2;
	}

	.modal-header {
		@apply .inline-block .border .rounded .shadow-2xl .bg-purple-700 .px-3 .py-2 .w-full .text-white .font-bold;
	}

</style>
