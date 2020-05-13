<template>
	<modal name="add-role-modal" id="add-role-modal" height="auto">
		<div class="box h-64">
			<div class="flex flex-1">
				<h1 class="font-bold box-header py-2"> Add a new role </h1>
			</div>
			<div class="grid grid-cols-6 mt-10">
				<div class="col-start-3 col-end-5">
					<div class="flex w-full">
						<input class="input-text" type="text" id="newRoleName" name="newRoleName"
						       placeholder="name">
					</div>

					<button class="btn-green"></button>
				</div>
			</div>
		</div>
	</modal>
</template>

<script>
    export default {
        methods: {
            addRole() {
                alert('called role');
            }
        },
        name: "AddRoleModal"
    }
</script>

<style scoped>

</style>