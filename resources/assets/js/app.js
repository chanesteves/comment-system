require('./bootstrap');

import VueResource from "vue-resource";

window.Vue = require('vue');
Vue.use(VueResource);

import CommentsComponent from './components/Comments.vue';

Vue.component('comments', CommentsComponent);
const app = new Vue({
    el: '#app'
});