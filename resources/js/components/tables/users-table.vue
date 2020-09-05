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
                        <option v-for="subscription in subscriptions" v-bind:value="subscription.id">
                            {{ subscription.plan }}
                        </option>
                    </select>
                </td>
                <!-- show icons based on user confirmation -->
                <td v-if="user.confirmed == 1">
                    <i class="cursor-pointer fas fa-check text-green-600" v-on:click="changeConfirmation(user.id, user.confirmed)"></i>
                </td>
                <td v-else>
                    <i class="cursor-pointer fas fa-ban text-red-600" v-on:click="changeConfirmation(user.id, user.confirmed)"></i>
                </td>
                <!-- show icons based on user lock -->
                <td v-if="user.locked == 1">
                    <i class="cursor-pointer fas fa-lock text-red-600" v-on:click="changeLock(user.id, user.locked)"></i>
                </td>
                <td v-else>
                    <i class="cursor-pointer fas fa-lock-open text-green-600" v-on:click="changeLock(user.id, user.locked)"></i>
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
            changeRole(userID, event)
            {
                axios.get('/change-role/'  + event.target.value + '/' + userID);
            },

            /*
             * function to change user's subscription
             */
            changeSubscription(userID, event)
            {

                axios.get('/change-subscriptions/' + event.target.value + '/' + userID);
            },

            /*
             * function to change user confirmation status
             */
            changeConfirmation(userID, confirmationStatus) {
                axios.patch('/users/' + userID, {
                    'confirmed': !confirmationStatus
                }).then(function () {
                    window.location.reload()
                });
            },

            /*
             * function to lock/unlock user
             */
            changeLock(userID, lockedStatus) {
                console.log(lockedStatus);
                axios.patch('/users/' + userID, {
                    'locked': !lockedStatus
                }).then(function () {
                    window.location.reload()
                })
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
    
    .table-header-row {
        @apply .bg-purple-700 .text-white .px-4 .py-2
    }

</style>
