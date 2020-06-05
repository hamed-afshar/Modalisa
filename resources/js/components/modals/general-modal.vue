<template>
    <div class="modal"  v-show="value">
        <modal-header v-on:closeModal="closeModal">
            {{ fields.title }}
        </modal-header>
        <slot name="modal-body"> </slot>
    </div>
</template>

<script>
    export default {
        name: "general-modal",
        props: {
            value: {
                required:true
            },
            fields: Object
        },
        data: {

        },
        methods: {
            closeModal() {
                this.value =! this.value;
            }
        }
    }
</script>

<style scoped>
    .modal-box {
        @apply .bg-white .text-gray-500 .border .rounded-lg .p-2;
    }
</style>
