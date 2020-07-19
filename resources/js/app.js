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


Vue.component('roles-table', require('./components/tables/roles-table').default);
Vue.component('permission-table', require('./components/tables/permissions-table').default);
Vue.component('circle-plus-button', require('./components/buttons/circle-plus-button').default);
Vue.component('add-role-modal', require('./components/modals/add-role-modal').default);
Vue.component('delete-role-modal', require('./components/modals/delete-role-modal').default);

Vue.component('add-permission-modal', require('./components/modals/add-permission-modal').default);
Vue.component('delete-permission-modal', require('./components/modals/delete-permission-modal').default);
Vue.component('edit-permission-modal', require('./components/modals/edit-permission-modal').default);




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const lang= document.documentElement.lang.substr(0,2);
const i18n = new VueInternationalization({
    locale:lang,
    messages:Locale
})

const app = new Vue({
    el: '#app',
    i18n,
});
