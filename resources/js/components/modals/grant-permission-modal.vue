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
			<div class="flex flex-col overflow-y-auto h-48">
				<table class="table-auto mb-2">
					<thead>
					<tr class="">
						<th class="table-header-cell">
							{{ $t('translate.grant_permission')}}
						</th>
					</tr>
					</thead>
					<tbody>
					<tr class="table-body-row" v-for="permission in associatedPermissions" v-bind:key="permission.id">
						<td class="table-body-cell">
							<div class="flex flex-row">
								<div class="w-4/12">
									{{ permission.name }}
								</div>
								<div class="w-7/12">
									{{ permission.label }}
								</div>
								<div class="w-1/12 flex flex-row">
									<i class="fas fa-minus minus-button cursor-pointer" v-on:click=""></i>
								</div>
							</div>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="flex flex-col overflow-y-auto h-48 mt-2">
				<table class="table-auto mb-2">
					<thead>
					<tr class="">
						<th class="table-header-cell">
							{{ $t('translate.all_permissions')}}
						</th>
					</tr>
					</thead>
					<tbody>
					<tr class="table-body-row" v-for="permission in allPermissions" v-bind:key="permission.id">
						<td class="table-body-cell">
							<div class="flex flex-row">
								<div class="w-4/12">
									{{ permission.name }}
								</div>
								<div class="w-7/12">
									{{ permission.label }}
								</div>
								<div class="w-1/12 flex flex-row">
									<i class="fas fa-plus plus-button cursor-pointer" v-on:click=""></i>
								</div>
							</div>
						</td>
					</tr>
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
                id: 1,
                associatedPermissions: [],
                allPermissions: []
            }
        },

        methods: {
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

<style scoped>
	.modal-box {
		@apply .bg-white .text-gray-500 .border .rounded-lg .p-2;
	}

	.modal-header {
		@apply .inline-block .border .rounded .shadow-2xl .bg-purple-700 .px-3 .py-2 .w-full .text-white .font-bold;
	}

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

	.th {
		position: sticky;
		top: 0;
	}
</style>