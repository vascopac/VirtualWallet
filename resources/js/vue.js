require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import Vuetify from 'vuetify';


Vue.use(Vuetify);
Vue.use(Vuex);
Vue.use(VueRouter);

import 'material-design-icons-iconfont/dist/material-design-icons.css';
import 'vuetify/dist/vuetify.min.css';
import Master from "./components/layouts/Master";
import store from "./store/store-global";
import routes from "./routes";
import VueSocketIO from "vue-socket.io";

const router = new VueRouter({
  routes:routes,
});

/*const socket = new VueSocketIO({
  debug: true,
  connection: 'http://projeto.dad:8080'
})*/

const app = new Vue({
  router,
  store,
  //socket,
  components: {Master},
    template: '<Master/>',
    created(){
      this.$store.commit('loadTokenAndUserFromSession');
    },
    vuetify: new Vuetify(),
}).$mount('#app');

