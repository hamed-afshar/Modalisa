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
                    <div class="flex flex-row">
                        <div class="w-5/6">
                            <a class="link" v-on:click="$modal.show('edit-permission-modal',
                            {id:permission.id, name:permission.name, label:permission.label}, {}, {'before-open':event=>{}})">
	                            {{ permission.name }}
                            </a>
                        </div>
	                    <div class="w-1/6 flex justify-end x-button">
		                    <i class="fas fa-times cursor-pointer" v-on:click="$modal.show('delete-permission-modal',
                            {id:permission.id, name:permission.name},{},
                            {'before-open':event => {event.params.id, event.params.name}})"></i>
	                    </div>
                    </div>
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
            title: $t('translate.add_permission'),
        }">
		</add-permission-modal>

        <delete-permission-modal v-bind:fields="{
            title: $t('translate.delete_permission'),
        }">
        </delete-permission-modal>

		<edit-permission-modal v-bind:fields="{
		   title : $t('translate.edit_permission')
		}">
		</edit-permission-modal>
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
                axios.get('/permissions')
                    .then(response => this.permissions = response.data);
                // close any modal causes this event
                this.$modal.hide('add-permission-modal');
	            this.$modal.hide('edit-permission-modal');
            },
	        //function to execute after deleting a permission from db
            delete() {
                // list permissions
                axios.get('/permissions')
                    .then(response => this.permissions = response.data);
                // close modal
                this.$modal.hide('delete-permission-modal')
            },
        },
        mounted() {
            //fetch all permissions immediately after loading
            axios.get('./permissions')
                .then(response => this.permissions = response.data);
            //listening for permission saving signal
            Event.$on('save', () => {
                this.save();
            })
	        //listening for permission deleting signal
            Event.$on('delete', () => {
                this.delete();
            })
        }
    }
</script>

<style scoped>

</style>
