require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
//import vuetify fro
import Vuetify from 'vuetify';


Vue.use(Vuetify);
Vue.use(Vuex);
Vue.use(VueRouter);

import 'material-design-icons-iconfont/dist/material-design-icons.css';
import 'vuetify/dist/vuetify.min.css';
import Master from "./components/layouts/Master";
import store from "./store/store-global";
import routes from "./routes";

//import vuetify from './plugins/vuetify'

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
    },
    vuetify: new Vuetify(),
}).$mount('#app');

