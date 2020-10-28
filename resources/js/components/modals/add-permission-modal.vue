<template>
    <modal name="add-permission-modal" id="add-permission-modal" height="auto" :adaptive="true"
           @before-open="beforeOpen"
           @before-close="beforeClose">
        <div class="modal-box">
            <div class="modal-header">
                <div class="flex flex-row">
                    <div class="w-1/2">
                        <h1 class="font-bold"> {{ fields.title }} </h1>
                    </div>
                    <div class="w-1/2 flex pt-1 justify-end">
                        <i class="fas fa-times cursor-pointer" v-on:click="$modal.hide('add-permission-modal')"></i>
                    </div>
                </div>
            </div>
            <div class="flex flex-col mt-6">
                <div>
                    <input class="input-text w-full" type="text" v-model="permissionName" id="permissionName"
                           name="permissionName"
                           placeholder="Name"
                           v-bind:maxlength="fields.maxName"
                           autofocus>
                </div>
                <!-- error section -->
                <div class="error">
                    {{ errors.get('name')}}
                </div>
                <div class="mt-2">
                    <input class="input-text w-full" type="text" v-model="permissionLabel" id="permissionLabel"
                           name="permissionLabel"
                           v-bind:maxlength="fields.maxLabel"
                           placeholder="Label">
                </div>
                <!-- error section -->
                <div class="error">
                    {{ errors.get('label')}}
                </div>
                <div class="mt-4">
                    <button class="btn-pink w-full px-4 py-2 mb-2" v-on:click="save"> {{ $t('translate.save') }}</button>
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
		    if(this.errors[field] ) {
		        return this.errors[field][0];
		    }
        }

        record(errors) {
		    this.errors = errors.errors
        }

        clear() {
            this.errors ={}
        }

	}
    export default {
        name: "add-permission-modal",
        props: {
            fields: Object
        },
        data() {
            return {
	            permissionName: null,
	            permissionLabel: null,
	            errors: new Errors()
            }
        },
	    methods: {
            save() {
				axios.post('/permissions', {
				    name: this.permissionName,
					label: this.permissionLabel
	            }).then(function() {
	                Event.$emit('save');
				}).catch(error => this.errors.record(error.response.data))
            },
            /*
             *function to be executed before opening modal
            */
		    beforeOpen() {
                //clear name and label fields
			    this.permissionLabel = null;
			    this.permissionName = null;
		    },

            /*
             *function to be executed before closing modal
            */
            beforeClose() {
                this.errors.clear();
            }
	    },
    }
</script>

<style>

</style>
