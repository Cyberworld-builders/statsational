
require('./bootstrap');


var Vue = require('vue');
Vue.component('Auction', require('./components/Auction.vue'));
Vue.component('Tabs', require('./components/Tabs.vue'));
Vue.component('auction-control', require('./components/auction/AuctionControl.vue'));
Vue.component('auction-form', require('./components/auction/AuctionForm.vue'));
Vue.component('join-form', require('./components/auction/AuctionJoinForm.vue'));
Vue.component('auction-bidding', require('./components/auction/AuctionBidding.vue'));
Vue.component('auction-items', require('./components/auction/AuctionItems.vue'));
Vue.component('bidders-overview', require('./components/auction/BiddersOverview.vue'));

// Vue.component('join-form', {props: ['auction'],template: '<h1>{{ auction }}</h1>'});

Vue.component('chat', require('./components/messages/Chat.vue'));
Vue.component('chat-messages', require('./components/messages/ChatMessages.vue'));
Vue.component('chat-form', require('./components/messages/ChatForm.vue'));



import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import Datetime from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

Vue.use(Datetime)

new Vue({
 el: '#app',
 // data: {
 //   messages: []
 // },
 // created() {
 //
 //     Echo.private('chat')
 //       .listen('MessageSent', (e) => {
 //         this.messages.push({
 //           message: e.message.message,
 //           user: e.user
 //         });
 //       });
 // },
 //
 // methods: {
 //     fetchMessages(auction) {
 //        // if(this.auction){
 //          axios.get('messages/' + auction).then(response => {
 //              this.messages = response.data;
 //          });
 //        // }
 //     },
 //
 //     addMessage(message) {
 //         axios.post('/messages', message).then(response => {
 //           console.log(response.data);
 //           this.messages.push(response.data);
 //         });
 //     }
 // }
});
