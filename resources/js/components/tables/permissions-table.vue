<template>
	<div class="flex flex-col">
		<table class="table-auto mb-2">
			<thead class="sticky top-0">
			<tr class="table-header-row">
				<th class="table-header-cell" v-show="columns.length" v-for="column in columns" :key="column">
					{{ column }}
				</th>
			</tr>
			</thead>
			<tbody>
			<tr class="table-body-row" v-for="permission in permissions" v-bind:key="permission.id">
				<td class="table-body-cell">
					<a class="link" v-bind:href="path + permission.id"> {{ permission.name }} </a>
				</td>
			</tr>
			</tbody>
		</table>
		<div class="flex justify-end transform -translate-y-6 translate-x-5">
			<button v-on:click="$modal.show('add-permission-modal')">
				<circle-plus-button></circle-plus-button>
			</button>
		</div>
		<add-permission-modal v-bind:fields="{
            title: 'Add a Permission',
            attr: {
                buttons:['Save'],
            },
        }">
		</add-permission-modal>
	</div>
</template>

<script>
    export default {
        name: "permission-table",
        props: {
            columns: Array,
        },
        data() {
            return {
                permissions: [],
                path: '/permissions/',
            }
        },
        methods: {
	        //function to execute after saving permission in db
            save() {
                axios.get('./permissions')
                    .then(response => this.permissions = response.data);
                // close modal
                this.$modal.hide('add-permission-modal')
            }
        },

        mounted() {
            axios.get('./permissions')
                .then(response => this.permissions = response.data);
            Event.$on('save', () => {
                this.save();
            })
        }
    }
</script>

<style scoped>

</style>
