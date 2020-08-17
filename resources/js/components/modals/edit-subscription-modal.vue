<template>
	<modal name="edit-subscription-modal" id="edit-subscription-modal" height="auto" :adaptive="true"
			@before-open="beforeOpen">
		<div class="modal-box">
			<div class="modal-header">
				<div class="flex flex-row">
					<div class="w-1/2">
						<h1 class="font-bold"> {{ fields.title }} </h1>
					</div>
					<div class="w-1/2 flex pt-1 justify-end">
						<i class="fas fa-times cursor-pointer" v-on:click="$modal.hide('edit-subscription-modal')"></i>
					</div>
				</div>
			</div>
			<div class="grid grid-cols-6 mt-6">
				<div class="col-start-3 col-end-5">
					<div class="flex">
						<input class="input-text w-full" type="text" v-model="subscriptionPlan" id="subscriptionPlan"
						       name="subscriptionPlan"
						       placeholder="Plan Name"
						       v-bind:maxlength="fields.maxPlan"
						       autofocus>
					</div>
					<div class="flex mt-2">
						<input class="input-text w-full" type="text" v-model="cost" id="cost"
						       name="cost"
						       placeholder="Cost Percentage"
						       v-bind:maxlength="fields.maxCost">
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
        name: "edit-subscription-modal",
	    props: {
            fields: Object
	    },
	    data () {
            return {
                id: null,
	            subscriptionPlan: null,
	            cost: null,
	            errors: new Errors()
            }
	    },
	    methods: {
            /*
             *method to save subscription in db
             */
		    save() {
		        axios.patch('/subscriptions/' + this.id , {
		            plan: this.subscriptionPlan,
		            cost_percentage: this.cost
		        }).then(function () {
					Event.$emit('save');
                }).catch(error => this.errors.record(error.response.data))
		    },
            /*
			*function to be executed before opening modal
			*/
		    beforeOpen(event) {
		        //get parameters from subscription-table
			    this.id = event.params.id;
			    this.subscriptionPlan = event.params.plan;
			    this.cost = event.params.cost;
		    }

	    }
    }
</script>

<style scoped>

</style>