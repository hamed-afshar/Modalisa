<template>
	<modal name="grant-permission-modal" id="grant-permission-modal" height="auto"
	       @before-open="beforeOpen">
		<div class="modal-box">
			<div class="modal-header">
				<div class="flex flex-row">
					<div class="w-1/2">
						<h1 class="font-bold">{{ fields.title }}</h1>
					</div>
					<div class="w-1/2 flex pt-1 justify-end">
						<i class="fas fa-times cursor-pointer" v-on:click="$modal.hide('grant-permission-modal')"></i>
					</div>
				</div>
			</div>
			<div class="flex flex-col">
				<table class="table-auto mb-2">
					<thead>
					<tr>
						<th class="table-header-row">
							{{ $t('translate.grant_permission')}}
						</th>
					</tr>
					</thead>
					<tbody>
					<div class="overflow-y-auto h-48">
						<tr class="table-body-row flex w-full" v-for="permission in associatedPermissions"
						    v-bind:key="permission.id">
							<td class="w-4/12">
								{{ permission.name }}
							</td>
							<td class="w-7/12">
								{{ permission.label }}
							</td>
							<td class="w-1/12">
								<i class="fas fa-minus minus-button cursor-pointer" v-on:click="remove()"></i>
							</td>
						</tr>
					</div>
					</tbody>
				</table>
			</div>
			<div class="flex flex-col">
				<table class="table-auto mb-2">
					<thead>
					<tr>
						<th class="table-header-row">
							{{ $t('translate.all_permissions')}}
						</th>
					</tr>
					</thead>
					<tbody>
					<div class="overflow-y-auto h-48">
						<tr class="table-body-row flex w-full" v-for="permission in allPermissions"
						    v-bind:key="permission.id">
							<td class="w-4/12">
								{{ permission.name }}
							</td>
							<td class="w-7/12">
								{{ permission.label }}
							</td>
							<td class="w-1/12">
								<i class="fas fa-plus plus-button cursor-pointer" v-on:click="add()"></i>
							</td>
						</tr>
					</div>
					</tbody>
				</table>
			</div>
		</div>
	</modal>
</template>

<script>
    export default {
        name: "grant-permission-modal",
        props: {
            fields: Object,
        },
        data() {
            return {
                id: null,
                associatedPermissions: [],
                allPermissions: []
            }
        },

        methods: {
            //remove permission from granted permissions list
            remove() {
                axios.delete('')
            },
	        //assign new permission to the role
	        add() {

	        },
            //function to be executed before opening modal
            beforeOpen(event) {
                this.id = event.params.id
            },
        },
        mounted() {
            //fetch all associated permissions to the selected role
            axios.get('/associated-permissions/' + this.id)
                .then(response => this.associatedPermissions = response.data);
            //fetch all available permissions
            axios.get('/permissions')
                .then(response => this.allPermissions = response.data)
        }

    }

</script>

<style>
	.table-header-row {
		@apply .bg-white .text-gray-600 .px-4 .py-2
	}

	.table-body-row {
		@apply .bg-white .border .px-4 .py-2 .text-center .text-gray-800
	}

	.table-body-row:hover {
		@apply .bg-gray-200
	}

</style>