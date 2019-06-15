
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import Vuetify from 'vuetify'
import App from '../views/App.vue'
import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
import router from '../js/router'
import store from '../js/store'
import Toaster from 'v-toaster'

import 'v-toaster/dist/v-toaster.css'

Vue.use(Toaster, {
    timeout: 5000
})

Vue.use(Vuetify)
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('Home', require('./components/Home.vue'));

const app = new Vue({
    el: '#app',
    router,
    store,
     components: {
             App
         },
         render: h => h(App),
         methods: {

         }
});
