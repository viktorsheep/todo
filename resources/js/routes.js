// import VueRouter from 'vue-router'
import TaskList from './components/Task-list';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';

export const routes = [
    {
        path : '/',
        name : 'home',
        component : Home
    },

    {
      path : '/register',
      name : 'register',
      component : Register
    },

    {
      path : '/login',
      name : 'login',
      component : Login
    },

    {
      path : '/dashboard',
      name : 'dathboard',
      component : Dashboard
    },

    {
      path : '*',
      redirect : '/'
    }
]