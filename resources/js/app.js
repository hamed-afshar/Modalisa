/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Event = new Vue();

import VModal from 'vue-js-modal';
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated'


Vue.use(VueInternationalization);
Vue.use(VModal);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('guest-navbar', require('./components/navigation/guest-navbar').default);
Vue.component('admin-navbar', require('./components/navigation/admin-navbar').default);
Vue.component('general-navbar', require('./components/navigation/general-navbar').default);


Vue.component('admin-header', require('./components/headers/admin-header').default);

Vue.component('roles-table', require('./components/tables/roles-table').default);
Vue.component('permission-table', require('./components/tables/permissions-table').default);
Vue.component('users-table', require('./components/tables/users-table').default);
Vue.component('subscriptions-table', require('./components/tables/subscriptions-table').default);
Vue.component('orders-table-user', require('./components/tables/orders-table-user').default);
Vue.component('wallet-table-user', require('./components/tables/wallet-table-user').default);

Vue.component('circle-plus-button', require('./components/buttons/circle-plus-button').default);

Vue.component('add-role-modal', require('./components/modals/add-role-modal').default);
Vue.component('delete-role-modal', require('./components/modals/delete-role-modal').default);
Vue.component('edit-role-modal', require('./components/modals/edit-role-modal').default);

Vue.component('grant-permission-modal', require('./components/modals/grant-permission-modal').default);
Vue.component('add-permission-modal', require('./components/modals/add-permission-modal').default);
Vue.component('delete-permission-modal', require('./components/modals/delete-permission-modal').default);
Vue.component('edit-permission-modal', require('./components/modals/edit-permission-modal').default);

Vue.component('add-subscription-modal', require('./components/modals/add-subscription-modal').default);
Vue.component('delete-subscription-modal', require('./components/modals/delete-subscription-modal').default);
Vue.component('edit-subscription-modal', require('./components/modals/edit-subscription-modal').default);

Vue.component('user-sidebar', require('./components/dashboards/user-sidebar').default);
Vue.component('user-dashboard', require('./components/dashboards/user-dashboard').default);
Vue.component('dashboard-layout', require('./components/dashboards/dashboard-layout').default);


/**
 * include ziggy package for including routes from laravel to vue
 */
Vue.mixin({
    methods: {
        route: route
    }
});


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const lang= document.documentElement.lang.substr(0,2);
/**
 *add i18n package for vue localization
 * @type {Vue}
 */
const i18n = new VueInternationalization({
    locale:lang,
    messages:Locale
})
const app = new Vue({
    el: '#app',
    i18n,
});

