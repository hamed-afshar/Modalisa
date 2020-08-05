<template>
	<div class="flex flex-col" id="app">
		<table class="table-auto mb-2">
			<thead class="sticky top-0">
			<tr class="table-header-row">
				<th class="table-header-cell" v-show="columns.length" v-for="column in columns" :key="column">
					{{ column }}
				</th>
			</tr>
			</thead>
			<tbody>
			<tr class="table-body-row" v-for="role in roles" v-bind:key="role.id">
				<td class="table-body-cell">
					<div class="flex flex-row">
						<div class="w-5/12 flex justify-start">
							<a class="link" v-on:click="$modal.show('edit-role-modal',
							{id:role.id, name:role.name, label:role.label} ,{},
								  {'before-open':event=>{}})">
								{{ role.name }} </a>
						</div>
						<div class="w-6/12">
							<a class="link" v-on:click="$modal.show('edit-role-modal',
							{id:role.id, name:role.name, label:role.label} ,{},
								  {'before-open':event=>{}})">
								{{ role.label }} </a>
						</div>
						<div class="w-1/12 flex flex-row">
							<div class="w-1/2 flex justify-start link-button">
								<i class="fas fa-link cursor-pointer" v-on:click="$modal.show('grant-permission-modal',
								{id:role.id})"> </i>
							</div>
							<div class="w-1/2 flex justify-end x-button">
								<i class="fas fa-times cursor-pointer" v-on:click="$modal.show('delete-role-modal',
                            {id:role.id, name:role.name},{},
                            {'before-open':event => {event.params.id, event.params.name}})"></i>
							</div>
						</div>

					</div>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="flex justify-end transform -translate-y-6 translate-x-5">
			<button v-on:click="$modal.show('add-role-modal')">
				<circle-plus-button></circle-plus-button>
			</button>
		</div>
		<add-role-modal v-bind:fields="{
            title: $t('translate.add_role'),
            maxName: 15,
            maxLabel: 20
        }">
		</add-role-modal>

		<delete-role-modal v-bind:fields="{
		    title: $t('translate.delete_role'),
		}">
		</delete-role-modal>

		<edit-role-modal v-bind:fields="{
		    title: $t('translate.edit_role'),
		    maxName: 15,
            maxLabel: 20
		}">
		</edit-role-modal>

		<grant-permission-modal v-bind:fields="{
		    title: $t('translate.grant_permission')
		}">
		</grant-permission-modal>

	</div>
</template>

<script>
    export default {
        name: "roles-table",
        props: {
            columns: Array
        },
        data() {
            return {
                roles: [],
                path: '/roles/'
            }
        },
        methods: {
            //function to execute after saving a role in db
            save() {
                axios.get('/roles')
                    .then(response => this.roles = response.data);
                //close modal
                this.$modal.hide('add-role-modal');
                this.$modal.hide('edit-role-modal');
            },
            //function to execute after deleting a role from db
            delete() {
                // list roles
                axios.get('/roles')
                    .then(response => this.roles = response.data);
                // close modal
                this.$modal.hide('delete-role-modal')
            },
        },

        mounted() {
            //fetch all roles immediately after loading
            axios.get('/roles')
                .then(response => this.roles = response.data);
            //listening for role adding signal
            Event.$on('save', () => {
                this.save();
            });
            //listening for role deleting signal
            Event.$on('delete', () => {
                this.delete();
            })

        }
    }
</script>

<style>
	.table-header-row {
		@apply .bg-purple-700 .text-white
	}

	.table-header-cell {
		@apply .border .px-4 .py-2 .text-center
	}

	.table-body-row {
		@apply .bg-white
	}

	.table-body-cell {
		@apply .border .px-4 .py-2 .text-center .text-gray-800
	}

</style>
