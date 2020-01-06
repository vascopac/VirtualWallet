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
import Toasted from "vue-toasted";

const router = new VueRouter({
  routes:routes,
});

Vue.use(new VueSocketIO({
  debug: false,
  connection: 'http://localhost:8080'
}));

Vue.use(Toasted, {
    position: "bottom-center",
    duration: 5000,
    type: "info"
});
/*
const socket = new VueSocketIO({
  debug: true,
  connection: 'http://192.168.10.10:8080'
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

