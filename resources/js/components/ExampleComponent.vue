<template>
	<modal name="modalisa-modal" id="modalisa-modal" height="auto">
		<div class="box h-48">
			<div class="box-header">
				<div class="flex flex-row">
					<div class="w-1/2">
						<h1 class="font-bold"> Add New Role </h1>
					</div>
					<div class="w-1/2 flex pt-1 justify-end">
						<button @click="$emit('close')"></button>
					</div>
				</div>
			</div>
			<div class="grid grid-cols-6 mt-6">
				<div class="col-start-3 col-end-5">
					<div class="flex">
						<input class="input-text w-full" type="text" id="newRoleName" name="newRoleName"
						       placeholder="New Role Name" autofocus>
					</div>
					<div class="flex w-full mt-2">
						<button class="btn-pink w-full"> Save</button>
					</div>

				</div>
			</div>
		</div>
	</modal>
</template>

<script>
    export default {
        data() {
            return {
                showModal: false
            }
        }
    }
</script>
