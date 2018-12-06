import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);
import Home         from './components/booking/Home';
import SearchList   from './components/booking/SearchList';

let routes = [
    {
        path:'/',
        component: Home,
        name:'Home',
        meta: {
            isFull: true
        }
    }
];


export default new VueRouter({
    routes,
    mode: 'history'

});