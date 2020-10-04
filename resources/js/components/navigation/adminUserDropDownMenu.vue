<template>
  <div>
    <a href="#" class="top-navbar-menu-item"> Settings </a>
    <a href="#" class="top-navbar-menu-item" v-on:click="logout"> Sign Out </a>
  </div>
</template>

<script>
export default {
  name: "adminUserDropDownMenu",
  methods: {
    /*
   * logout user and redirect back to welcome page
   */
    logout() {
      axios.post('/logout')
          .then(() => location.href = './');
    },
  },
}
</script>

<style scoped>

</style>