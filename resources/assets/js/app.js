
require('./bootstrap');


var Vue = require('vue');
Vue.component('Auction', require('./components/Auction.vue'));
Vue.component('Tabs', require('./components/Tabs.vue'));
Vue.component('auction-control', require('./components/AuctionControl.vue'));

new Vue({
 el: '#app'
});
