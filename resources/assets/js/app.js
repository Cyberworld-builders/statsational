
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

var testLayout = [


];

import VueGridLayout from 'vue-grid-layout'

var GridLayout = VueGridLayout.GridLayout;
var GridItem = VueGridLayout.GridItem;

Vue.use(Datetime)


new Vue({
 el: '#app',
 components: {
   GridLayout,
   GridItem
 },

 data: {
   layout: [
        {id: "Items","x":0,"y":0,"w":9,"h":9,"i":"0"},
        {id: "Bidders","x":9,"y":0,"w":3,"h":18,"i":"2"},
        {id: "Chat","x":2,"y":10,"w":7,"h":9,"i":"3"},
        {id: "Queue","x":0,"y":10,"w":2,"h":9,"i":"4"}
     ],
   auction: {
     item: {
       name: "",
       bids: [
         {
           amount: 0,
           bidder: {
             name: ""
           }
         }
       ]
     }
   },

   user: {
     bid: {
       amount: 0,
       minimum: 0
     }
   },

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

   sorted_bidders: [],

   name: "",
   modalShow: false,
   imActive: true,
   time_remaining: "",
   timer: "",
   status: "Paused",

   selectedBidder: false,
   showCompletedItems: false,
   showBidEditor: false,
   showMinimumBidWarning: false,
   showOwnerControls: false,
   showBiddersOverviewDetail: false,

   bid_increment: 5,

   bid_amount: 1

 },
 methods: {

     // bidding

     bid(){
       this.resetTimer();
       this.user.bid.amount = this.bid_amount;
       if(this.auction.status.status.in_progress == true){
         if(this.bid_amount >= this.user.bid.minimum){
           axios.post('/auctions/bid',{
             auction_id: this.auction.id,
             bid_amount: this.user.bid.amount,
             item_id: this.auction.item.id
           }).then(function(response){
             this.updatePool(response.data.auction);
           }.bind(this)).catch(e => {
             console.log(e);
           });
         } else {
           this.showMinimumBidWarning = true;
           this.user.bid.amount = this.user.bid.minimum;
           this.bid_amount = this.user.bid.minimum;
         }
       }


     },
     bidMinimum(){
       this.user.bid.amount = this.getMinimumBid();
       this.bid();
     },
     lowerBid(){
       var new_bid = Number(this.user.bid.amount) - this.auction.bid_increment;
       if(new_bid > this.user.bid.minimum){
         this.user.bid.amount = new_bid;
       }
       // this.user.bid.amount--;
       this.bid_amount = this.user.bid.amount;
     },
     raiseBid(){
       var minimum_bid = this.user.bid.minimum;
       var new_bid = Number(this.user.bid.amount) + this.auction.bid_increment;
       if(new_bid >= minimum_bid){
         this.user.bid.amount = new_bid;
       }
       // this.user.bid.amount++;
       this.bid_amount = this.user.bid.amount;
     },
     getMinimumBid(){
       var lowest_bid = 0;
       for(var i=0;i<this.auction.item.bids.length;i++){
         if(this.auction.item.bids[i].amount > lowest_bid){
           lowest_bid = Number(this.auction.item.bids[i].amount);
         }
       }
       return lowest_bid + this.auction.bid_increment;
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

     getTimeRemaining(){
       axios.get('/auctions/timer/' + this.auction.id).then(response => {
         var status = response.data;
         this.time_remaining = status.time_remaining;
         this.auction.status.status = status.status;
         this.updateClock();
       });
     },

     countDown(){
        var current_time_remaining = this.time_remaining;
        this.getTimeRemaining();
         if(this.auction.user.id == document.getElementById('user_id').value){
           if(this.auction.queue.length > 0 ){
             if(this.auction.status.status.in_progress == true && this.time_remaining > 0 && current_time_remaining == this.time_remaining){
                this.resetTimer(this.time_remaining - 1);
             } else {
               if(this.auction.manual_next == 0){
                 this.startNextItem();
               }
             }
           }
         }
     },

     toggleStatus(){
       if(this.auction.status.status.in_progress){
         this.auction.status.status.label = "Paused";
         this.auction.status.status.in_progress = false;
       } else {
         this.auction.status.status.label = "In Progress";
         this.auction.status.status.in_progress = true;
       }
       this.setStatus(this.auction.status.status);
     },

     setStatus(status){
       axios.post('/auctions/status',{
         auction: this.auction.id,
         in_progress: status.in_progress,
         label: status.label
       }).then(response => {
         // console.log(response.data);
         // this.updatePool();
       }).catch(e => {
         console.log(e);
       });
     },

     resetTimer(seconds){
        if(typeof(seconds) == "undefined"){
          if(this.time_remaining <= this.auction.snipe_time){
            this.time_remaining = this.auction.snipe_time;
          } else {
            this.time_remaining = this.auction.bid_timer;
          }
          seconds = this.time_remaining;
        }
         axios.post('/auctions/timer',{
           auction: this.auction.id,
           seconds: seconds
         }).then(response => {
           var status = response.data;
           this.time_remaining = status.time_remaining;
           this.updateClock();
         }).catch(e => {
           console.log(e);
         });
     },

     undoLastBid(){

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
         this.item = response.data.item;
       }).catch(e => {
         console.log(e);
       });
     },

     updateClock(){
       var timer = document.getElementById('timer');
         if(this.time_remaining <= this.auction.snipe_time){
           timer.classList.add('blinking');
         } else {
           timer.classList.remove('blinking');
         }
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
         this.getAuctionData(this.auction.id);
         this.time_remaining = this.auction.bid_timer;
         this.updateClock();
       }).catch(e => {
         console.log(e);
       });
     },

     getAuctionData(auction_id){
       axios.get('/auction/data/' + auction_id,{
       }).then(response => {
         this.updatePool(response.data);
         if(this.user.id == this.auction.user.id){
           this.showOwnerControls = true;
         }
        this.fetchMessages();
       }).catch(e => {
         console.log(e);
       });
     },

     updateSelectedBidder(bidder_id){
       this.showBiddersOverviewDetail = true;
       axios.post('/auction/bidder/data' ,{
         bidder_id: bidder_id,
         auction_id: this.auction.id
       }).then(response => {
         if(response.data.id){
           this.selectedBidder = response.data;
           this.bidders[this.selectedBidder.id] = this.selectedBidder;
           this.calculateBidderStats();
         }
       }).catch(e => {
         console.log(e);
       });
     },

     inArray(needle,haystack){
       for(var i=0;i<haystack.length;i++){
         if(needle == haystack[i]){
           return true;
         }
       }
       return false;
     },

     calculateBidderStats(){
       var bidders = Object.values(this.bidders);
       var spend_values = {};
       for(var i=0;i<bidders.length;i++){
         spend_values[bidders[i].id] = bidders[i].spend;
       }
       var sorted_values = [];
       for (var bidder in spend_values){
         sorted_values.push([bidder,spend_values[bidder]]);
       }
       sorted_values.sort(function(a,b){
         return b[1] - a[1];
       });
       this.sorted_bidders = [];
       for(var i=0;i<sorted_values.length;i++){
         var bidder = this.bidders[ sorted_values[i][0] ];
           if(bidder.item_count > 0){
             this.bidders[bidder.id].average_spend = Math.round(bidder.spend / bidder.item_count);
           } else {
             this.bidders[bidder.id].average_spend = 0;
           }
         bidder.average_spend = this.bidders[bidder.id].average_spend;
         this.sorted_bidders.push( bidder );
       }
     },

     updatePool(auction){
       this.auction = auction;
       this.auction.items = [];
       this.bidders = auction.bidders;
       this.calculateBidderStats();

       this.user = this.bidders[this.user.id];
       this.user.bid = {
         minimum: 0,
         amount: 0
       };
       this.user.bid.amount = this.getMinimumBid();
       this.user.bid.minimum = this.getMinimumBid();

       this.bid_amount = this.user.bid.minimum;

       if(auction.bid_timer > 0){
         this.auction.bid_timer = auction.bid_timer;
       } else {
         this.auction.bid_timer = 45;
       }

       if(auction.snipe_time > 0){
         this.auction.snipe_time = auction.snipe_time;
       } else {
         this.auction.snipe_time = 15;
       }

       if(this.time_remaining == ""){
         this.time_remaining = this.auction.bid_timer;
       }

       if(this.timer == ""){
         this.timer = "0:" + this.auction.bid_timer;
       }

       if(!this.auction.status){
         this.auction.status = {
           status: {
             in_progress: false,
             label: "Not Started"
           }
         };
       }

       if(!this.auction.status.status){
         this.auction.status.status = {
           in_progress: false,
           label: "Not Started"
         };
       }

       console.log(auction);
     },

     formatMoney(number){
       return number;
     },

     isActive(item_id){
       // for(var i=0;i<this.auction.items.length; i++){
         for(var i=0;i<this.auction.queue.length;i++){
           if(item_id == this.auction.queue[i].id){
             return true;
           }
         }
       // }
       return false;
     }

 },

 mounted() {
   if(document.getElementById('user_id')){
     this.user.id = document.getElementById('user_id').value;
   }
   if(document.getElementById('auction_id')){
     this.getAuctionData(document.getElementById('auction_id').value);
     setInterval(function(){ this.countDown() }.bind(this),1000);
   }
 },

 created() {
   // let the same message event handle all real-time updates
   Echo.private('chat')
     .listen('MessageSent', (e) => {
       if(e.type == "chat"){
         this.fetchMessages();
       } else if (e.type == "bid"){
         this.getAuctionData(document.getElementById('auction_id').value);
         var current_bid = document.getElementById('current_bid');
         current_bid.classList.add('blinking');
         setTimeout(function(){ current_bid.classList.remove('blinking') },2000);
       } else if (e.type == "timer"){
         this.getTimeRemaining();
       } else if (e.type == "status"){
         this.getAuctionData(this.auction.id);
       } else if (e.type == "next") {
         this.getTimeRemaining();
         this.auction.queue = e.message.queue;
       } else if (e.type == "update"){
         this.auction = e.message;
         this.getAuctionData(document.getElementById('auction_id').value);
       } else if (e.type == "switchItem"){
         this.updateCurrentItem(e.message);
         this.getTimeRemaining();
       }
     });
 }

});
