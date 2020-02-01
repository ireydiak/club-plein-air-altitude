/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import { BootstrapVue } from 'bootstrap-vue'
import Routes from './router';

const Vue = require('vue');

Vue.use(BootstrapVue);
Vue.prototype.$eventBus = new Vue();
Vue.prototype.$router = Routes.Routes;

window.Vue = Vue;
require('vue-resource');

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    next();
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

Vue.prototype.$eventBus.$on('flash-message', (message, type) => {
    console.log(message);
});

Vue.prototype.$submit = function(url, data, context, successCallback, errorCallback) {
    context.$http.post(url, data).then(response => {
        context.$bvToast.toast(response.body.message, {
            title: 'Succès!',
            autoHideDelay: 5000,
            appendToast: false,
            variant: 'success'
        });
        console.log(response);

        if (successCallback) {
            successCallback(response);
        }
    }).catch(error => {
        context.$bvToast.toast(error.body.message, {
            title: 'Erreur',
            autoHideDelay: 15000,
            appendToast: false,
            variant: 'danger'
        });
        console.error(error);

        context.errors = error.body.errors;

        if (errorCallback) {
            errorCallback(error);
        }
    });
};
