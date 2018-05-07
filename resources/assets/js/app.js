
require('./bootstrap');


var Vue = require('vue');
Vue.component('Auction', require('./components/Auction.vue'));
Vue.component('Tabs', require('./components/Tabs.vue'));
Vue.component('auction-control', require('./components/AuctionControl.vue'));
Vue.component('auction-form', require('./components/AuctionForm.vue'));

// var VueResource = require('vue-resource');
// Vue.use(VueResource);
//
// Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content');
// Vue.http.options.emulateJSON = true;

import Datetime from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

Vue.use(Datetime)

new Vue({
 el: '#app'
});
