<template>
	<modal name="edit-role-modal" id="edit-role-modal" height="auto"
			@before-open="beforeOpen">
		<div class="modal-box">
			<div class="modal-header">
				<div class="flex flex-row">
					<div class="w-1/2">
						<h1 class="font-bold"> {{ fields.title }} </h1>
					</div>
					<div class="w-1/2 flex pt-1 justify-end">
						<i class="fas fa-times cursor-pointer" v-on:click="$modal.hide('edit-role-modal')"></i>
					</div>
				</div>
			</div>
			<div class="grid grid-cols-6 mt-6">
				<div class="col-start-3 col-end-5">
					<div class="flex">
						<input class="input-text w-full" type="text" v-model="roleName" id="roleName"
						       name="roleName"
						       placeholder="Name"
						       v-bind:maxlength="fields.maxLabel"
						       autofocus>
					</div>
					<div class="flex mt-2">
						<input class="input-text w-full" type="text" v-model="roleLabel" id="roleLabel"
						       name="roleLabel"
						       placeholder="Label"
						       v-bind:maxlength="fields.maxName">
					</div>
					<div class="flex w-full mt-4">
						<button class="btn-pink w-full" v-on:click="save"> {{ $t('translate.save')}}</button>
					</div>
					<div class="error">
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
        name: "edit-role-modal",
        props: {
            fields: Object
        },
        data() {
            return {
                id: null,
                roleName: null,
                roleLabel: null,
                errors: new Errors()
            }
        },
        methods: {
	        //method to save role in db
            save() {
                axios.patch('/roles/' + this.id, {
                    name: this.roleName,
                    label: this.roleLabel,
                }).then(function () {
                    Event.$emit('save');
                }).catch(error => this.errors.record(error.response.data))
            },
            beforeOpen(event) {
                this.id = event.params.id;
                this.roleName = event.params.name;
                this.roleLabel = event.params.label;
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
