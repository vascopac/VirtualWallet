require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex';

Vue.use(Vuex);
Vue.use(VueRouter);

import Master from "./components/layouts/Master";
import store from "./store/store-global";
import routes from "./routes";

const router = new VueRouter({
  routes:routes,
});

const app = new Vue({
  router,
    store,
  components: {Master},
    template: '<Master/>',
    created(){
      this.$store.commit('loadTokenAndUserFromSession');
    }
}).$mount('#app');

