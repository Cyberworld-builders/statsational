
require('./bootstrap');


var Vue = require('vue');
Vue.component('Auction', require('./components/Auction.vue'));
Vue.component('Tabs', require('./components/Tabs.vue'));
Vue.component('auction-control', require('./components/AuctionControl.vue'));
Vue.component('auction-form', require('./components/AuctionForm.vue'));
Vue.component('join-form', require('./components/AuctionJoinForm.vue'));
// Vue.component('join-form', {props: ['auction'],template: '<h1>{{ auction }}</h1>'});

Vue.component('chat-messages', require('./components/messages/ChatMessages.vue'));
Vue.component('chat-form', require('./components/messages/ChatForm.vue'));



// var VueResource = require('vue-resource');
// Vue.use(VueResource);
//
// Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content');
// Vue.http.options.emulateJSON = true;

import Datetime from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

Vue.use(Datetime)

new Vue({
 el: '#app',
 data: {
   messages: []
 },
 created() {
     this.fetchMessages();
     Echo.private('chat')
       .listen('MessageSent', (e) => {
         this.messages.push({
           message: e.message.message,
           user: e.user
         });
       });
 },

 methods: {
     fetchMessages() {
         axios.get('/messages').then(response => {
             this.messages = response.data;
         });
     },

     addMessage(message) {
         this.messages.push(message);

         axios.post('/messages', message).then(response => {
           console.log(response.data);
         });
     }
 }
});
