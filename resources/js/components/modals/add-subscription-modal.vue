<template>
	<modal name="add-subscription-modal" id="add-subscription-modal" height="auto" :adaptive="true"
	       @before-open="beforeOpen"
	       @before-close="beforeClose">
		<div class="modal-box">
			<div class="modal-header">
				<div class="flex flex-row">
					<div class="w-1/2">
						<h1 class="font-bold"> {{ fields.title }} </h1>
					</div>
					<div class="w-1/2 flex pt-1 justify-end">
						<i class="fas fa-times cursor-pointer" v-on:click="$modal.hide('add-subscription-modal')"></i>
					</div>
				</div>
			</div>
			<div class="flex flex-col mt-6">
				<div>
					<input class="input-text w-full" type="text" v-model="subscriptionPlan" id="subscriptionPlan" name="subscriptionPlan"
					       placeholder="Plan Name"
					       v-bind:maxlength="fields.maxPlan"
					       autofocus>
				</div>
				<!-- error section -->
				<div class="error">
					{{ errors.get('plan')}}
				</div>
				<div class="mt-2">
					<input class="input-text w-full" type="number" v-model="cost"
                           id="cost"
                           name="cost"
					       placeholder="Cost Percentage"
					       v-on:input="checkInput">
				</div>
				<!-- error section -->
				<div class="error">
					{{ errors.get('cost_percentage')}}
				</div>
				<div class="mt-4">
					<button class="btn-pink px-4 py-2 w-full" v-on:click="save"> {{ $t('translate.save')}}</button>
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
        name: "add-subscription-modal",
	    props: {
            fields: Object
	    },
	    data() {
            return {
                subscriptionPlan: null,
	            cost: null,
	            errors: new Errors()
            }
	    },
	    methods: {
            /*
             * function to check input for cost value
             */
            checkInput() {
              if(this.cost.length > 2) {
                  this.cost = this.cost.slice(0, this.fields.maxCost);
              }
            },

		    /*
             * save function
             */
		    save() {
				axios.post('/subscriptions',{
				    plan: this.subscriptionPlan,
					cost_percentage: this.cost
			    }).then(function() {
			        Event.$emit('save');
				}).catch(error => this.errors.record(error.response.data));
		    },
		    /*
		     * function to be executed before opening modal
		     */
            beforeOpen() {
                //clear plan and percentage fields
	            this.subscriptionPlan = null;
	            this.cost = null;
            },

            /*
		     * function to be executed before closing modal
		     */
            beforeClose() {
                this.errors.clear();
            }

	    }
    }
</script>

<style scoped>

</style>
