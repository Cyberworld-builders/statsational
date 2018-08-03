
require('./bootstrap');


var Vue = require('vue');

Vue.component('Auction', require('./components/Auction.vue'));
Vue.component('Tabs', require('./components/Tabs.vue'));
Vue.component('auction-control', require('./components/auction/AuctionControl.vue'));
Vue.component('auction-form', require('./components/auction/AuctionForm.vue'));
Vue.component('join-form', require('./components/auction/AuctionJoinForm.vue'));
Vue.component('auction-items', require('./components/auction/AuctionItems.vue'));
Vue.component('grid-test',require('./components/GridTest.vue'));

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import Datetime from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

import axios from 'axios'
import moment from 'moment'

window.$ = window.jQuery = require('jquery');
import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/ui/widgets/resizable.js';
import 'jquery-ui/ui/widgets/draggable.js';


Vue.use(Datetime)


new Vue({
 el: '#app',

 mounted() {
   $('.resizable').resizable();
   $('.datapicker').datepicker();

 },



});
