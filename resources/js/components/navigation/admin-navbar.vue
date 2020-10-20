<template>
  <header class="bg-gray-300">
    <div class="flex items-center justify-between px-4 py-2">
      <!-- Left Side -->
      <div>
        <logo/>
      </div>
      <!-- Right Side -->
      <div>
        <div class="relative px-2 flex flex-row-reverse items-center">
          <div class="px-2">
            <button v-on:click="userMenuOpen =! userMenuOpen" class="block">
              <i class="hidden sm:block fas fa-user fa-3x top-navbar-icon"></i>
              <i class="block sm:hidden fas fa-bars fa-3x top-navbar-icon"></i>
            </button>

          </div>
          <div class="px-2 pt-2">
            <button v-on:click="notificationMenuOpen =! notificationMenuOpen" class="block relative">
              <i class="fas fa-bell fa-3x top-navbar-icon"></i>
              <span class="badge"> 32 </span>
            </button>
            <!-- Hidden button to close menus when clicking on the screen -->
            <button v-if="userMenuOpen || notificationMenuOpen" v-on:click="closeMenu" tabindex="-1"
                    class="fixed inset-0 w-full h-full bg-black opacity-50 cursor-default"></button>
            <!-- User Menu Items -->
            <div v-if="userMenuOpen" class="absolute w-48 right-0 mt-3 mr-4 py-2 top-navbar-menu">
              <admin-user-drop-down-menu/>
            </div>
            <!-- Notification Menu Items -->
            <div v-if="notificationMenuOpen" class="absolute w-64 right-0 mt-3 mr-4 py-2 top-navbar-menu">
              <notification-menu/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import Logo from "./logo";
import AdminUserDropDownMenu from "./adminUserDropDownMenu";
import NotificationMenu from "./notificationMenu";

export default {
  name: "auth-navbar",
  components: {NotificationMenu, AdminUserDropDownMenu, Logo},
  data() {
    return {
      userMenuOpen: false,
      notificationMenuOpen: false
    }
  },
  methods: {
    /*
     * close all open menus
     */
    closeMenu() {
      this.userMenuOpen = false;
      this.notificationMenuOpen = false
    }
  },
  created() {
    /*
     * listen for pressing Esc key to close the menu
     */
    const handleEscape = (e) => {
      if (e.key === 'Esc' || e.key === 'Escape') {
        this.userMenuOpen = false
      }
    }
    document.addEventListener('keydown', handleEscape)
    this.$once('hook:beforeDestroy', () => {
      document.removeEventListener('keydown', handleEscape)
    })
  },

}
</script>

<style scoped>


</style>
