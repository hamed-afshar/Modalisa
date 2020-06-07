<template>
	<div name="modal-content" id="modal-content">
		<div class="modal-box">
			<div class="grid grid-cols-6 mt-6">
				<div class="col-start-3 col-end-5">
					<div v-for="item in fields.attr.inputs">
						<div class="flex mt-2">
							{{ item.name}}
							<input class="input-text w-full" type="text" id="field" name="roleName"
							       placeholder= "" autofocus>
						</div>
					</div>
					<div class="flex w-full mt-4">
						<button class="btn-pink w-full"> save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
    export default {
        name: "modal-content",
        props: {
            fields: Object,
        }
    }
</script>

<style scoped>
	.modal-box {
		@apply .bg-white .text-gray-500 .border .rounded-lg .p-2;
	}
</style>
