<template>
	<modal name="edit-permission-modal" id="edit-permission-modal" height="auto"
			@before-open="beforeOpen">
		<div class="modal-box">
			<div class="modal-header">
				<div class="flex flex-row">
					<div class="w-1/2">
						<h1 class="font-bold"> {{ fields.title }} </h1>
					</div>
					<div class="w-1/2 flex pt-1 justify-end">
						<i class="fas fa-times cursor-pointer" v-on:click="$modal.hide('edit-permission-modal')"></i>
					</div>
				</div>
			</div>
			<div class="grid grid-cols-6 mt-6">
				<div class="col-start-3 col-end-5">
					<div class="flex">
						<input class="input-text w-full" type="text" v-model="permissionName"
                               id="permissionName"
						       name="permissionName"
                               autofocus
						       v-bind:maxlength="fields.maxName"
                               >
					</div>
					<div class="flex mt-2">
						<input class="input-text w-full" type="text" v-model="permissionLabel"
                               id="permissionLabel"
						       name="permissionLabel"
						       v-bind:maxlength="fields.maxLabel">
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
        name: "edit-permission-modal",
        props: {
            fields: Object
        },
        data() {
            return {
                id: null,
                permissionName: null,
                permissionLabel: null,
                errors: new Errors()
            }
        },
        methods: {
	        /*
	         *method to save permission in db
	         */
            save() {
                axios.patch('/permissions/' + this.id, {
                    name: this.permissionName,
                    label: this.permissionLabel
                }).then(function () {
                    Event.$emit('save');
                }).catch(error => this.errors.record(error.response.data))
            },
            /*
             *function to be executed before opening modal
             */
            beforeOpen(event) {
                //get parameters from permission-table modal
                this.id = event.params.id;
                this.permissionName = event.params.name;
                this.permissionLabel = event.params.label;
            }
        },
    }
</script>

<style scoped>

</style>
