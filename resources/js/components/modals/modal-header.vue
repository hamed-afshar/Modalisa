<template>
    <div name="modal-header" id="modal-header">
        <div class="modal-header">
            <div class="flex flex-row">
                <div class="w-1/2">
                    <h1 class="font-bold">
                        <slot></slot>
                    </h1>
                </div>
                <div class="w-1/2 flex pt-1 justify-end">
                    <i class="fas fa-times cursor-pointer" v-on:click="closeModal"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "modal-header",
        methods: {
            closeModal() {
                this.$root.$emit('close-modal')
            }
        }
    }
</script>

<style scoped>
    .modal-header {
        @apply .inline-block .border .rounded .shadow-2xl .bg-purple-700 .px-3 .py-2 .w-full .text-white .font-bold;
    }
</style>
