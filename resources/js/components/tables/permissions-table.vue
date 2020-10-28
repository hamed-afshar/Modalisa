<template>
  <div>
    <div class="table-back">
      <table class="table-design">
        <thead class="sticky top-0 border-b">
        <tr></tr>
        </thead>
        <tbody>
        <div class="overflow-y-auto">
          <tr class="table-body-row flex flex-row" v-for="permission in permissions" v-bind:key="permission.id">
            <td class="w-2/6 table-body-cell">
              <a class="link" v-on:click="$modal.show('edit-permission-modal',
                            {id:permission.id, name:permission.name, label:permission.label}, {},
                                  {'before-open':event=>{}})">
                {{ permission.name }}
              </a>
            </td>
            <td class="w-3/6 table-body-cell">
              <a class="link" v-on:click="$modal.show('edit-permission-modal',
                            {id:permission.id, name:permission.name, label:permission.label}, {},
                                  {'before-open':event=>{}})">
                {{ permission.label }}
              </a>
            </td>
            <td class="w-1/6 table-body-cell">
              <i class="fas fa-times cursor-pointer x-button" v-on:click="$modal.show('delete-permission-modal',
                            {id:permission.id, name:permission.name},{},
                            {'before-open':event => {event.params.id, event.params.name}})">
              </i>
            </td>
          </tr>
        </div>
        </tbody>
      </table>
      <add-permission-modal v-bind:fields="{
            title: $t('translate.add_permission'),
            maxName: 15,
            maxLabel: 20
        }">
      </add-permission-modal>

      <delete-permission-modal v-bind:fields="{
            title: $t('translate.delete_permission'),
        }">
      </delete-permission-modal>

      <edit-permission-modal v-bind:fields="{
		   title : $t('translate.edit_permission'),
		   maxName: 15,
           maxLabel: 20
		}">
      </edit-permission-modal>
    </div>
    <div class="flex justify-end transform -translate-y-6 translate-x-1 md:-translate-y-6 md:translate-x-5">
      <button v-on:click="$modal.show('add-permission-modal')">
        <circle-plus-button></circle-plus-button>
      </button>
    </div>
  </div>

</template>

<script>
export default {
  name: "permission-table",
  props: {},
  data() {
    return {
      permissions: [],
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
    axios.get('/permissions')
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
