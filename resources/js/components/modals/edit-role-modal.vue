<template>
    <modal name="edit-role-modal" id="edit-role-modal" height="auto"
           @before-open="beforeOpen"
           @before-close="beforeClose">
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
            <div class="flex flex-col mt-6">
                <div>
                    <input class="input-text w-full" type="text" v-model="roleName" id="roleName"
                           name="roleName"
                           v-bind:maxlength="fields.maxLabel"
                           autofocus>
                    <!-- error section -->
                    <div class="error">
                        {{ errors.get('name')}}
                    </div>
                    <div class="mt-2">
                        <input class="input-text w-full" type="text" v-model="roleLabel" id="roleLabel"
                               name="roleLabel"
                               v-bind:maxlength="fields.maxName">
                    </div>
                    <!-- error section -->
                    <div class="error">
                        {{ errors.get('label')}}
                    </div>
                    <div class="mt-4">
                        <button class="btn-pink w-full" v-on:click="save"> {{ $t('translate.save')}}</button>
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

        clear() {
            this.errors ={}
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
	        /*
	         *method to save role in db
	         */
            save() {
                axios.patch('/roles/' + this.id, {
                    name: this.roleName,
                    label: this.roleLabel,
                }).then(function () {
                    Event.$emit('save');
                }).catch(error => this.errors.record(error.response.data))
            },

            /*
             *function to be executed before opening modal
             */
            beforeOpen(event) {
                //get parameters from roles-table modal
                this.id = event.params.id;
                this.roleName = event.params.name;
                this.roleLabel = event.params.label;
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

<style scoped>

</style>
