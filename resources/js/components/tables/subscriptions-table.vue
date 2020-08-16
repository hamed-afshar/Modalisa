<template>
	<div>
		<table class="table-auto w-full">
			<thead>
			<tr></tr>
			</thead>
			<tbody>
			<div class="overflow-y-auto">
				<tr class="table-body-row flex flex-row" v-for="subscription in subscriptions"
				    v-bind:key="subscription.id">
					<td class="w-2/5">
						<a class="link">
							{{ subscription.plan }}
						</a>
					</td>
					<td class="w-2/5">
						<a class="link">
							{{ subscription.cost_percentage }}
						</a>
					</td>
					<td class="w-1/5 flex justify-end">
						<i class="fas fa-times cursor-pointer x-button"
						   v-on:click="$modal.hide('add-subscription-modal')">
						</i>
					</td>
				</tr>
			</div>
			</tbody>
		</table>
		<div class="flex justify-end transform  -translate-y-4 translate-x-1 md:-translate-y-6 md:translate-x-5">
			<button v-on:click="$modal.show('add-subscription-modal')">
				<circle-plus-button></circle-plus-button>
			</button>
		</div>
		<add-subscription-modal v-bind:fields="{
		    title: $t('translate.add_subscription'),
		    maxPlan: 15,
		    maxCost: 2
		}">

		</add-subscription-modal>

	</div>
</template>

<script>
    export default {
        name: "subscriptions-table",
	    data() {
            return {
                subscriptions: []
            }
	    },
	    methods: {
            /*
             * function to execute after saving a subscription in db
             */
		    save() {
		        axios.get('/subscriptions')
			        .then(response => this.subscriptions = response.data);
		        //close modal
			    this.$modal.hide('add-subscription-modal')
		    }

	    },
	    mounted() {
            /*
             * fetch all subscriptions immediately after loading
             */
		    axios.get('/subscriptions')
			    .then(response => this.subscriptions = response.data);
			/*
			 * listening for subscription adding signal
			 */
		    Event.$on('save', () => {
		        this.save();
            })
        }
    }
</script>

<style>

</style>