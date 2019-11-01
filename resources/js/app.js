require('./bootstrap');

import axios from 'axios';
import Vue from 'vue';
window.Vue = require('vue'); // temp comment : to delete
window.axios = axios;

import VueRouter from 'vue-router';
import Vuex from 'vuex';
import Vuelidate from 'vuelidate';

import {routes} from './routes.js';
import storeData from './store.js';
import MainApp from './components/MainApp.vue';

import ElementUI from 'element-ui';
import 'moment';

// element ui css fixture
import 'element-ui/lib/theme-chalk/index.css'; 
import 'element-ui/lib/theme-chalk/reset.css';
import locale from 'element-ui/lib/locale/lang/en';

// OLD CODE : to delete later
// setting components : 
// v todo : to change to routes.js 
Vue.component('task-list', require('./components/Task-list.vue').default);
Vue.component('login', require('./pages/Login.vue').default);

// -----

Vue.use(ElementUI, {locale}); // using element ui
Vue.use(Vuelidate);
Vue.use(VueRouter);
Vue.use(Vuex);

// config : router
const router = new VueRouter({
    mode : 'history',
    base: process.env.BASE_URL,
    routes,
});

router.beforeEach((to, from, next) => {
    if(to.fullPath === '/dashboard') {
        if (!store.state.currentUser) {
            next('/');
        }
    }

    else if(to.fullPath === '/login') {
        if (store.state.currentUser) {
            next('/dashboard');
        }
    }

    else if(to.fullPath === '/register') {
        if (store.state.currentUser) {
            next('/dashboard');
        }
    }

    else if(to.fullPath === '/') {
        if (store.state.currentUser) {
            next('/dashboard');
        }
    }

    next();
});

const store = new Vuex.Store(storeData);

const app = new Vue({
    el : '#app',
    router,
    store,
    // render : h => h(MainApp),
    template : '<main-App/>',
    components : {
	    MainApp
    },
});
