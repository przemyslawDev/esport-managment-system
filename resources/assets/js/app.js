
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('pagination', require('./components/Pagination.vue'));

Vue.component('dashboard', require('./views/Dashboard.vue'));
Vue.component('users', require('./views/users/Users.vue'));
Vue.component('user', require('./views/users/User.vue'));
Vue.component('user-create', require('./views/users/UserCreate.vue'));
Vue.component('user-edit', require('./views/users/UserEdit.vue'));

//Administration module components
require('../../../Modules/Administration/Assets/js/app');

const app = new Vue({
    el: '#app'
});
