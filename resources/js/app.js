require('./bootstrap');

// import 'es6-promise/auto';
import axios from 'axios';
// import VueAuth from '@websanova/vue-auth';
// import VueAxios from 'vue-axios';
// import VueRouter from 'vue-router';
// import auth from './auth'
// import router from './router'


window.Vue = require('vue');
window.axios = axios;

import ElementUI from 'element-ui';
import 'moment';
// require('element-ui/lib/theme-chalk/index.css');
import 'element-ui/lib/theme-chalk/index.css';
import 'element-ui/lib/theme-chalk/reset.css';
import locale from 'element-ui/lib/locale/lang/en';

// Vue.router = router;
// Vue.use(VueRouter);
// Vue.use(VueAxios, axios);
// axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api/v1`;
// Vue.use(VueAuth, auth);


Vue.use(ElementUI, {locale});

Vue.component('task-list', require('./components/Task-list.vue').default);
Vue.component('login', require('./pages/Login.vue').default);

const app = new Vue({
    el : '#app',
    // router
});