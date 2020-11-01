<template>
  <div class="flex h-screen bg-gray-200">
    <user-sidebar></user-sidebar>
    <div class="flex-1 flex flex-col overflow-hidden">
      <general-navbar></general-navbar>
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
        <div class="container mx-auto px-6 py-8">
          <user-dashboard></user-dashboard>
        </div>
      </main>

    </div>
  </div>
</template>

<script>
export default {
  name: "dashboard-layout"
}
</script>

<style scoped>

</style>