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
					<a class="link" v-bind:href="path + role.id"> {{ role.name }} </a>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="flex justify-end transform -translate-y-6 translate-x-5">
			<button v-on:click="$modal.show('add-role-modal')">
				<circle-plus-button></circle-plus-button>
			</button>
		</div>
		<add-role-modal title="Add a New Role"></add-role-modal>
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
            save() {
                axios.get('./roles')
                    .then(response => this.roles = response.data);
                this.$modal.hide('add-role-modal');
            }
        },
        created() {
            axios.get('./roles')
                .then(response => this.roles = response.data);
            Event.$on('save', () => {
                this.save();
            });
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
