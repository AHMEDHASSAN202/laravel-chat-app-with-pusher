
require('./bootstrap');
window.Vue = require('vue');

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('chat-list', require('./components/ChatList.vue').default);
Vue.component('online-users-component', require('./components/OnlineUsersComponent.vue').default);
// Vue.component('messages-component', require('./components/MessagesComponent.vue').default);

const app = new Vue({
    el: '#app',
});
