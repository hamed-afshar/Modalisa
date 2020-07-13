<template>
	<modal name="add-role-modal" id="add-role-modal" height="auto">
		<div class="modal-box h-64">
			<div class="modal-header">
				<div class="flex flex-row">
					<div class="w-1/2">
						<h1 class="font-bold"> {{ fields.title }} </h1>
					</div>
					<div class="w-1/2 flex pt-1 justify-end">
						<i class="fas fa-times cursor-pointer" v-on:click="$modal.hide('add-role-modal')"></i>
					</div>
				</div>
			</div>
			<div class="grid grid-cols-6 mt-6">
				<div class="col-start-3 col-end-5">
					<div class="flex">
						<input class="input-text w-full" type="text" v-model="roleName" id="roleName" name="roleName"
						       placeholder="Name" autofocus>
					</div>
					<div class="flex mt-2">
						<input class="input-text w-full" type="text" v-model="roleLabel" id="roleLabel" name="roleLabel"
						       placeholder="Lable" autofocus>
					</div>
					<div class="flex w-full mt-4">
						<button class="btn-pink w-full" v-on:click="save"> {{ $t('translate.save')}}</button>
					</div>
					<div>
						{{ errors.get('name')}}
						{{ errors.get('label')}}
					</div>
				</div>
			</div>
		</div>
	</modal>
</template>

<script>
    class Errors {
        constructor() {
            this.errors = {}
        }

        get(field) {
            if (this.errors[field]) {
                return this.errors[field][0];
            }
        }

        record(errors) {
            this.errors = errors.errors
        }
    }

    export default {
        name: "add-role-modal",
        props: {
            fields: Object
        },
        data() {
            return {
                roleName: null,
                roleLabel: null,
                errors: new Errors()
            }
        },
        methods: {
            save() {
                axios.post('/roles', {
                    name: this.roleName,
                    label: this.roleLabel
                }).then(function () {
                    Event.$emit('save');
                }).catch(error => this.errors.record(error.response.data));
            }
        }
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
