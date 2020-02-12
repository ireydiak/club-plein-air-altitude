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


Vue.prototype.$eventBus.$on('flash-message', (message, type) => {
    console.log(message);
});

Vue.toasted.register('api_error', (payload) => {
    if (!payload.message) {
        return 'Erreur serveur';
    }

    return payload.message;
}, {
    type: 'error',
    position: 'top-right',
    keepOnHover: true,
    duration: 5000
});

Vue.toasted.register('api_success', (payload) => {
    if (!payload.message) {
        return 'Succès';
    }

    return payload.message;
}, {
    type: 'success',
    position: 'top-right',
    keepOnHover: true,
    duration: 5000
});

Vue.prototype.$submitModel = function(model, context, successCallback, errorCallback) {
    model.save().then((response) => {
        console.log(response);
        context.$toasted.global.api_success(response.data.message);

        model.reset();

        if (successCallback) {
            successCallback(response);
        }
    }).catch((error) => {
        console.error(error);

        let message = error.body || error.message || '';

        context.$toasted.global.api_error(message);

        if (context.errors) {
            context.errors = error.errors;
        }

        if (errorCallback) {
            errorCallback(error);
        }
    });
};

Vue.prototype.$submit = function(url, data, context, successCallback, errorCallback) {
    context.$http.post(url, data).then(response => {
        context.$toasted.global.api_success(response.body);

        console.log(response);

        if (successCallback) {
            successCallback(response);
        }
    }).catch(error => {
        console.error(error);
        let message = error.body || error.message || '';


        context.$toasted.global.api_error(message);

        context.errors = error.body.errors;

        if (errorCallback) {
            errorCallback(error);
        }
    });
};
