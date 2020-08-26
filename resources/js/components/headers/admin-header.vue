<template>
	<div>
		<header class="container flex bg-white bg-pink-800 text-white font-bold justify-center border rounded-lg mt-4 py-2 mx-auto w-full">
			{{ $t('translate.system_admin_dashboard') }}
		</header>
		<div class="mt-4">
			<div class="container mx-auto">
				<div class="grid grid-rows-1">
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
						<div class="box h-48 flex items-center justify-center shadow-2xl">
							<div class="flex flex-wrap">
								<a v-bind:href="route('user-center')">
									<i class="box-item fa fa-user fa-3x flex justify-center w-full"></i>
									<div class="flex text-sm font-bold justify-center mt-2 w-full">{{
										$t('translate.user_center') }}
									</div>
								</a>
							</div>
						</div>
						<div class="box flex items-center justify-center shadow-2xl">
							<div class="flex flex-wrap">
								<a v-bind:href="route('security-center')">
									<i class="box-item fa fa-shield-alt fa-3x flex justify-center w-full"></i>
									<div class="flex text-sm font-bold justify-center mt-2 w-full">{{
										$t('translate.security_center') }}
									</div>
								</a>
							</div>
						</div>
						<div class="box flex items-center justify-center shadow-2xl">
							<div class="flex flex-wrap">
								<a href="#">
									<i class="box-item fas fa-money-check-alt fa-3x flex justify-center w-full"></i>
									<div class="flex text-sm font-bold justify-center mt-2 w-full">{{
										$t('translate.financial_center') }}
									</div>
								</a>
							</div>
						</div>
						<div class="box flex items-center justify-center shadow-2xl">
							<div class="flex flex-wrap">
								<a href="#">
									<i class="box-item fas fa-book-reader fa-3x flex justify-center w-full"></i>
									<div class="flex text-sm font-bold justify-center mt-2 w-full">{{
										$t('translate.report_center') }}
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</template>

<script>
    export default {
        name: "admin-header",
	    methods: {
	    }
    }
</script>

<style scoped>

</style>