require('./bootstrap');

import VueResource from "vue-resource";

window.Vue = require('vue');
Vue.use(VueResource);

import CommentComponent from './components/Comment.vue';

Vue.component('comment', CommentComponent);
const app = new Vue({
    el: '#app'
});