<template>
  <div>
    <div class="table-back">
      <table class="table-design">
        <thead class="border-b">
        <tr></tr>
        </thead>
        <tbody>
        <div class="overflow-y-auto">
          <tr class="table-body-row flex flex-row" v-for="role in roles" v-bind:key="role.id">
            <td class="w-5/12 table-body-cell">
              <a class="link" v-on:click="$modal.show('edit-role-modal',
							{id:role.id, name:role.name, label:role.label} ,{},
								  {'before-open':event=>{}})">
                {{ role.name }}
              </a>
            </td>
            <td class="w-5/12 table-body-cell">
              <a class="link" v-on:click="$modal.show('edit-role-modal',
							{id:role.id, name:role.name, label:role.label} ,{},
								  {'before-open':event=>{}})">
                {{ role.label }}
              </a>
            </td>
            <td class="w-2/12 justify-between flex table-body-cell">
              <div class="w-1/2">
                <i class="fas fa-link cursor-pointer link-button" v-on:click="$modal.show('grant-permission-modal',
								{id:role.id})">
                </i>
              </div>
              <div class="w-1/2">
                <i class="fas fa-times cursor-pointer x-button" v-on:click="$modal.show('delete-role-modal',
                            {id:role.id, name:role.name},{},
                            {'before-open':event => {event.params.id, event.params.name}})"></i>
              </div>
            </td>
          </tr>
        </div>

        </tbody>
      </table>
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
    <div class="flex justify-end transform  -translate-y-4 translate-x-1 md:-translate-y-6 md:translate-x-5">
      <button v-on:click="$modal.show('add-role-modal')">
        <circle-plus-button></circle-plus-button>
      </button>
    </div>
  </div>

</template>

<script>
    export default {
        name: "roles-table",
        props: {
        },
        data() {
            return {
                roles: [],
            }
        },
        methods: {
            /*
             *function to execute after saving a role in db
             */
            save() {
                axios.get('/roles')
                    .then(response => this.roles = response.data);
                //close modal
                this.$modal.hide('add-role-modal');
                this.$modal.hide('edit-role-modal');
            },
            /*
             *function to execute after deleting a role from db
             */
            delete() {
                // list roles
                axios.get('/roles')
                    .then(response => this.roles = response.data);
                // close modal
                this.$modal.hide('delete-role-modal')
            },
        },

        mounted() {
            /*
             *fetch all roles immediately after loading
             */
            axios.get('/roles')
                .then(response => this.roles = response.data);
            /*
             *listening for role adding signal
             */
            Event.$on('save', () => {
                this.save();
            });
            /*
             *listening for role deleting signal
             */
            Event.$on('delete', () => {
                this.delete();
            })

        }
    }
</script>

<style>

</style>
