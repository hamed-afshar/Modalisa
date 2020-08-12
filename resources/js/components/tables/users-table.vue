<template>
    <div>
        <table class="auto w-full">
            <thead>
            <tr class="table-header-row">
                <th class="table-header-cell"> {{ $t("translate.id") }}</th>
                <th class="table-header-cell"> {{ $t("translate.name") }} </th>
                <th class="table-header-cell"> {{ $t("translate.email") }} </th>
                <th class="table-header-cell"> {{ $t("translate.subscription") }} </th>
                <th class="table-header-cell"> {{ $t("translate.confirmed") }} </th>
                <th class="table-header-cell"> {{ $t("translate.locked") }} </th>
            </tr>
            </thead>
            <tbody>
                <div class="overflow-y-auto">
                    <tr class="table-body-row flex flex-row" v-for="user in users" v-bind:key="user.id">
                        <td> {{user.id}}</td>
                        <td> {{user.name}}</td>
                        <td> {{user.email}}</td>
                        <td> {{user.subscription}}</td>
                        <td> {{user.confirmed}}</td>
                        <td> {{user.locked}}</td>
                    </tr>
                </div>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "users-table",
        props: {
        },
        data() {
            return {
                users: [],
            }
        },
        methods: {

        },
        mounted() {
          /*
           * fetch all users details from db
           */
            axios.get('/users')
                .then(response => this.users = response.data);
        }
    }
</script>

<style>
    .modal-box {
        @apply .bg-white .text-gray-500 .border .rounded-lg .p-2;
    }

    .modal-header {
        @apply .inline-block .border .rounded .shadow-2xl .bg-purple-700 .px-3 .py-2 .w-full .text-white .font-bold;
    }

    .table-header-row {
        @apply .bg-white .text-gray-600 .px-4 .py-2
    }

    .table-body-row {
        @apply .bg-white .border .px-4 .py-2 .text-center .text-gray-800
    }

    .table-body-row:hover {
        @apply .bg-gray-100
    }

</style>
