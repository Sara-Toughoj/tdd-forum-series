/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

window.Vue.prototype.authorize = function (handler) {

    let user = window.App.user;
    return user ? handler(user) : false;
}


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('paginator', require('./components/Paginator').default);
Vue.component('user-notifications', require('./components/UserNotifications').default);
Vue.component('thread-view', require('./pages/Thread').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.events = new Vue();
window.flash = function (message, level = 'success') {
    window.events.$emit('flash', {
        message: message,
        level: level
    });
};

const app = new Vue({
    el: '#app',
});


