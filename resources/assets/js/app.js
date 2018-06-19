
require('./bootstrap');

var Vue = require('vue');

Vue.component('Auction', require('./components/Auction.vue'));
Vue.component('Tabs', require('./components/Tabs.vue'));
Vue.component('auction-control', require('./components/auction/AuctionControl.vue'));
Vue.component('auction-form', require('./components/auction/AuctionForm.vue'));
Vue.component('join-form', require('./components/auction/AuctionJoinForm.vue'));
Vue.component('auction-items', require('./components/auction/AuctionItems.vue'));

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import Datetime from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

import axios from 'axios'
import moment from 'moment'

Vue.use(Datetime)

new Vue({
 el: '#app',
 data: {
   auction: {
     item: {
       name: "",
       bids: [
         {
           amount: 1,
           bidder: {
             name: ""
           }
         }
       ]
     }
   },
   item: {},
   user: {
     bid: {
       amount: 1,
       minimum: 1
     }
   },
   bids: [],
   bid_amount: 0,
   item_id: 0,
   minimum_bid: 1,
   high_bid: {},
   high_bidder: "",
   newMessage: '',
   messagers: [{ name: "Everyone" }],
   messageTo: "Everyone",
   messages: [],
   bidders: [
     {
       name: "",
       items: [
         { }
       ]
     }
   ],
   name: "",
   modalShow: false,
   imActive: true,
   time_remaining: "30",
   timer: "0:30",
   current_item: {
     high_bid: {
       amount: 0,
       bidder: {
         name: ""
       }
     }
   },
   new_bid: {},

   current_bid: false,
   my_bid: {
     amount: 1,
     minimum: 1
   },

   selectedBidder: false

 },
 methods: {

     // bidding

     bid(){
       axios.post('/auctions/bid',{
         auction_id: this.auction.id,
         bid_amount: this.user.bid.amount,
         item_id: this.auction.item.id
       }).then(function(response){
         console.log(response);
         this.updatePool(response.data.auction);
         this.resetTimer(30);
       }.bind(this)).catch(e => {
         console.log(e);
       });
     },
     bidMinimum(){
       this.user.bid.amount = this.getMinimumBid();
       this.bid();
     },
     lowerBid(){
       var new_bid = Number(this.user.bid.amount) - 1;
       if(new_bid > this.getCurrentBid()){
         this.user.bid.amount = new_bid;
       }
     },
     raiseBid(){
       var minimum_bid = this.getMinimumBid();
       var new_bid = Number(this.user.bid.amount) + 1;
       if(new_bid >= minimum_bid){
         this.user.bid.amount = new_bid;
       }
     },
     getMinimumBid(){
       var lowest_bid = 1;
       for(var i=0;i<this.auction.item.bids.length;i++){
         if(this.auction.item.bids[i].amount > lowest_bid){
           lowest_bid = Number(this.auction.item.bids[i].amount);
         }
       }
       return lowest_bid + 1;
     },
     getCurrentBid(){
       var current_bid = 0;
       for(var i=0;i<this.auction.item.bids.length;i++){
         if(this.auction.item.bids[i].id > current_bid){
           current_bid = Number(this.auction.item.bids[i].amount);
         }
       }
       return current_bid;
     },

     getStatus(){
       if(this.imActive){
         axios.get('/auction/users/' + this.auction.id, {})
           .then(response => {
             this.auction.users = response.data;

             for(var b=0; b<this.bidders.length;b++){
               for(var u=0;u<response.data;u++){
                 if (this.bidders[b].id == response.data[u].id){
                   this.bidders[b].active = response.data[u].active;
                 }
               }
               this.bidders[b].active = "true";
             }

             // console.log(this.bidders);
             // console.log(this.bidders);
           });
       }
     },

     // items widget/form
     showModal: function(){
       this.$refs.myModalRef.show();
     },
     hideModal: function(){
       this.$refs.myModalRef.hide();
     },

     addItem: function(){
       var items = this.auction.items;
       axios.post('/auctions/addItem',{
         name: this.name,
         auction_id: this.auction.id
       }).then(response => {
         this.getAuctionData(this.auction.id);
       }).catch(e => {
         console.log(e);
       });
       // this.auction.items = items;
       this.hideModal();
     },

     // messaging

     //
     sendMessage() {
         axios.post('/messages', {
             user: this.user,
             message: this.newMessage,
             auction: this.auction.id
         }).then(response => {
           this.messages.unshift(response.data);
         });
         this.newMessage = ''
     },

     // gets all group messages for the auction
     fetchMessages() {
         axios.get('messages/' + this.auction.id).then(response => {
             this.messages = response.data;
         });
     },

     countDown(){
       var timer = document.getElementById('timer');
       if(this.time_remaining <=10){
         timer.classList.add('blinking');
       } else {
         timer.classList.remove('blinking');
       }
       if(this.time_remaining > 0){
         this.time_remaining--;
         this.timer = moment().startOf('day')
           .seconds(this.time_remaining)
           .format('m:ss');
       }
     },

     resetTimer(seconds){
         axios.post('/auctions/timer',{
           seconds: seconds
         }).then(response => {
           this.time_remaining = seconds;
           this.updateClock();
         }).catch(e => {
           console.log(e);
         });
     },

     updateClock(){
       this.timer = moment().startOf('day')
         .seconds(this.time_remaining)
         .format('m:ss');
     },

     switchToItem(item){
       // axios.get('/auctions/item/switch/' + item.id,{
       //
       // }).then(response => {
       //   console.log(response.data);
       //   this.updateCurrentItem(response.data);
       //   this.time_remaining = 30;
       //   this.updateClock();
       // }).catch(e => {
       //   console.log(e);
       // });
     },

     updateCurrentItem(item){
       axios.get('/auction/data/' + Number(this.auction.id),{
       }).then(response => {
         console.log(response.data.item);
         this.item = response.data.item;
       }).catch(e => {
         console.log(e);
       });
     },

     updateClock(){
       this.timer = moment().startOf('day')
         .seconds(this.time_remaining)
         .format('m:ss');
     },

     startNextItem(){
       axios.post('/auctions/items/next',{
         auction_id: this.auction.id,
         item_id: this.auction.item.id,
         bid_id: this.getCurrentBid()
       }).then(response => {
         console.log(response.data);
         this.getAuctionData(this.auction.id);
         this.time_remaining = 30;
         this.updateClock();
       }).catch(e => {
         console.log(e);
       });
     },

     getAuctionData(auction_id){
       axios.get('/auction/data/' + auction_id,{
       }).then(response => {
         console.log(response);
         this.updatePool(response.data);
       }).catch(e => {
         console.log(e);
       });
     },

     updatePool(auction){
       this.auction = auction;
       this.bidders = auction.bidders;
       this.user.bid.amount = this.getMinimumBid();
     }

 },

 mounted() {
   if(document.getElementById('auction_id')){
     this.getAuctionData(document.getElementById('auction_id').value);
     setInterval(function(){ this.countDown() }.bind(this),1000);
   }

 },

 created() {

   if(this.auction.id){
     this.fetchMessages();
   }


   // let the same message event handle all real-time updates
   Echo.private('chat')
     .listen('MessageSent', (e) => {
       if(e.type == "chat"){
         console.log(e);
         this.messages.unshift({
           message: e.message.message,
           user: e.user,
           created_at: e.message.created_at
         });
       } else if (e.type == "bid"){
         console.log(e);
         this.getAuctionData(document.getElementById('auction_id').value);
         // this.updatePool(e.message.auction);
         var current_bid = document.getElementById('current_bid');
         current_bid.classList.add('blinking');
         setTimeout(function(){ current_bid.classList.remove('blinking') },2000);
       } else if (e.type == "timer"){
         this.time_remaining = Number(e.message);
         this.updateClock();
       } else if (e.type == "next") {
         this.time_remaining = 30;
         this.updateClock();
         this.auction.queue = e.message.queue;
       } else if (e.type == "update"){
         this.auction = e.message;
         this.getAuctionData(document.getElementById('auction_id').value);
       } else if (e.type == "switchItem"){
         console.log(e.message);
         this.updateCurrentItem(e.message);
         this.time_remaining = 30;
         this.updateClock();
       }
     });

 }


});
