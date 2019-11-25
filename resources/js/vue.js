import Login from "./components/Auth/Login";

require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

import Master from "./components/layouts/Master";
import HomeComponent from "./components/Home";
import LoginComponent from "./components/Auth/Login";
import RegisterComponent from "./components/Auth/Register";


const routes = [
  { path: '/', component: HomeComponent },
  { path: '/login', component: LoginComponent },
  { path: '/register', component: RegisterComponent },
];

const router = new VueRouter({
  routes:routes,
});

const app = new Vue({
  router,
  components: {Master},
    template: '<Master/>'
}).$mount('#app');

