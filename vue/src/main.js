// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'

window.axios = axios;

window.axios.defaults.headers.common = {
    //'Accept': 'application/hal+json',
    //'Authorization': '',
    'Content-Type': 'application/hal+json',
    'X-CSRF-Token': '0BOSQmGYGECrK1jWnhE2Utza6dtuhXlumBJdeRZ-9Js'
};

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})
