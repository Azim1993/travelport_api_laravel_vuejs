require('./bootstrap');
window.Vue = require('vue');

Vue.component('welcome', require('./components/Welcome.vue'));


import router from './router';
const app = new Vue({
    el: '#app',
    router
});
