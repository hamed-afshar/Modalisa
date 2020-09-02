<template>
    <div>
        <table class="table-auto w-full">
            <thead>
            <tr class="table-header-row">
                <th> {{ $t("translate.id") }}</th>
                <th> {{ $t("translate.name") }}</th>
                <th> {{ $t("translate.email") }}</th>
                <th> {{ $tc("translate.role", 1)}}</th>
                <th> {{ $tc("translate.subscription" , 1) }}</th>
                <th> {{ $t("translate.confirmed") }}</th>
                <th> {{ $t("translate.locked") }}</th>
            </tr>
            </thead>
            <tbody>
            <tr class="table-body-row" v-for="user in users" v-bind:key="user.id">
                <td> {{ user.id }}</td>
                <td> {{ user.name }}</td>
                <td> {{ user.email }}</td>
                <!-- show user's role in first row and list of available roles afterward -->
                <td>
                    <select v-on:change="changeRole(user.id, $event)">
                        <option  selected disabled>
                            {{ user.role.name }}
                        </option>
                        <option v-for="role in roles" v-bind:value="role.id">
                            {{ role.name }}
                        </option>
                    </select>
                </td>
                <!-- show user's role in first row and list of available roles afterward -->
                <td>
                    <select v-on:change="changeSubscription(user.id, $event)">
                        <option selected disabled>
                            {{ user.subscription.plan }}
                        </option>
                        <option v-for="subscription in subscriptions" v-bind:value="{'subscriptionID' : subscription.id}">
                            {{ subscription.plan }}
                        </option>
                    </select>
                </td>
                <!-- show icons based on user confirmation -->
                <td v-if="user.confirmed == 1">
                    <i class="cursor-pointer fas fa-check text-green-600" v-on:click="changeConfirmation"></i>
                </td>
                <td v-else>
                    <i class="cursor-pointer fas fa-ban text-red-600" v-on:click="changeConfirmation"></i>
                </td>
                <!-- show icons based on user lock -->
                <td v-if="user.locked == 1">
                    <i class="cursor-pointer fas fa-lock text-red-600" v-on:click="changeLock"></i>
                </td>
                <td v-else>
                    <i class="cursor-pointer fas fa-lock-open text-green-600" v-on:click="changeLock"></i>
                </td>

            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "users-table",
        props: {},
        data() {
            return {
                users: [],
                subscriptions: [],
                roles: [],
            }
        },
        methods: {
            /*
             * function to change user's role
             */
            changeRole(userID, event) {
                axios.get('/change-role/'  + event.target.value + '/' + userID);
            },

            /*
             * function to change user's subscription
             */
            changeSubscription(userID, event) {
                console.log('user-id: ' + userID);
                console.log('role-id: ' + event.target.value);
                axios.post('')
            },

            /*
             * function to change user confirmation status
             */
            changeConfirmation() {
                console.log('change confiramtion')
            },

            /*
             * function to lock/unlock user
             */
            changeLock() {
                console.log('change lock')
            }
        },
        mounted() {
            /*
             * fetch all users details from db
             */
            axios.get('/users')
                .then(response => this.users = response.data);

            /*
			 * fetch all roles from db
			*/
            axios.get('/roles')
                    .then(response => this.roles = response.data);

            /*
             * fetch all subscriptions from db
             */
            axios.get('/subscriptions')
                .then(response => this.subscriptions = response.data);
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
        @apply .bg-purple-700 .text-white .px-4 .py-2
    }

    .table-body-row {
        @apply .bg-white .border .px-4 .py-2 .text-center .text-gray-800
    }

    .table-body-row:hover {
        @apply .bg-gray-100
    }

</style>
