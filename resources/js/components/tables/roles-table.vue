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
            <tr class="table-body-row" v-for="role in roles" v-bind:key="role.id">
                <td class="table-body-cell">
                    {{ role.name }}
                </td>
            </tr>
            </tbody>
        </table>
        <div class="flex justify-end transform -translate-y-6 translate-x-5">
            <div class="btn-circle-pink">
                <i class="fas fa-plus"></i>
            </div>
        </div>
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
                roles: []
            }
        },
        mounted() {
            axios.get('./roles')
                .then(response => this.roles = response.data);
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
