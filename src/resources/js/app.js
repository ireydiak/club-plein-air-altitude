/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue';
import Routes from './router';
import vuetify from './vuetify';
import VueToasted from 'vue-toasted';

Vue.prototype.$eventBus = new Vue();
Vue.prototype.$router = Routes;
Vue.use(VueToasted);

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

new Vue({
    vuetify
}).$mount("#app");

Vue.toasted.register('api_error', (message) => {
    if (!message) {
        return 'Erreur serveur';
    }

    return message;
}, {
    type: 'error',
    position: 'top-right',
    keepOnHover: true,
    duration: 5000
});

Vue.toasted.register('api_success', (message) => {

    if (!message) {
        return 'Succès';
    }

    return message;
}, {
    type: 'success',
    position: 'top-right',
    keepOnHover: true,
    duration: 5000
});

Vue.prototype.$updateModel = function(model, context, successCallback, errorCallback) {
    return model.update().then(response => {
        console.log(response);

        context.$toasted.global.api_success(response.data.message);

        return response.data.member;
    }).catch(error => {
        console.error(error);

        let message = error.body || error.message || error.data.message || '';

        context.$toasted.global.api_error(message);

        if (context.errors) {
            context.errors = error.errors;
        }

        if (errorCallback) {
            errorCallback(error);
        }
    });
};

Vue.prototype.$submitModel = function(model, context, successCallback, errorCallback) {
    axios.post('/members', model.toJSON()).then((response) => {
        console.log(response);
        context.$toasted.global.api_success(response.data.message);

        model.reset();

        if (successCallback) {
            successCallback(response);
        }

        return response.data.member;
    }).catch((error) => {
        console.error(error.response);

        let message = error.body || error.response.data.message ||  '';
        let errors = error.response.data.errors || {};

        context.$toasted.global.api_error(message);

        if (context.errors) {
            context.errors = errors;
        }

        if (errorCallback) {
            errorCallback(error);
        }
    });
};

