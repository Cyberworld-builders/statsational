
require('./bootstrap');


var Vue = require('vue');

Vue.component('auction', require('./components/auction/Auction.vue'));
Vue.component('Tabs', require('./components/Tabs.vue'));
Vue.component('auction-control', require('./components/auction/AuctionControl.vue'));
Vue.component('auction-form', require('./components/auction/AuctionForm.vue'));
Vue.component('join-form', require('./components/auction/AuctionJoinForm.vue'));
Vue.component('auction-items', require('./components/auction/AuctionItems.vue'));
Vue.component('grid-test',require('./components/GridTest.vue'));
Vue.component('auction-summary',require('./components/auction/AuctionSummary.vue'));


import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import Datetime from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

import axios from 'axios'
import moment from 'moment'


import 'lodash/lodash.js'

window.$ = window.jQuery = require('jquery');
import 'jquery-ui/ui/core.js';
import 'jquery-ui/ui/widget.js';
import 'jquery-ui/ui/data.js';
import 'jquery-ui/ui/widgets/resizable.js';
import 'jquery-ui/ui/widgets/draggable.js';
import 'jquery-ui/ui/widgets/mouse.js';




import 'gridstack/dist/gridstack.js';
// import 'gridstack/src/gridstack.jQueryUI.js';


Vue.use(Datetime)


new Vue({
 el: '#app',

 data: {
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

   csv_items: [],
   itemsCsv: '',

   user: {
     bid: {
       amount: 0,
       minimum: 0
     }
   },

   newMessage: '',
   messagers: [{ name: "Everyone" }],
   messageTo: 'Everyone',
   messages: [],
   notifications: {
     messages: {

     }
   },

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

   importItemsWarning: "",
   showImportItemsWarning: false,


   bid_increment: 5,

   bid_amount: 1

 },
 methods: {


   axiosGet(path,id,callback){
     axios.get( path + '/' + id,{
     }).then(response => {
       if(callback){
         callback(response.data);
       } else {
         return response.data;
       }
     }).catch(e => {
       console.log(e);
     });
   },

   axiosPost(path,data,callback){
     axios.post( path, data ).then(response => {
       if(callback){
         callback(response.data);
       } else {
         return response.data;
       }
     }).catch(e => {
       console.log(e);
     });
   },


     // bidding

     reverseItems(){

       var items = this.auction.items;
       var queue = this.auction.queue;

       var completed = [];

       var queue_ids = [];

       for(var i=0;i<queue.length;i++){
         queue_ids.push(queue[i].id);
       }

       for(var i=0;i<items.length;i++){
         if( ! this.inArray( items[i].id, queue_ids ) ){
           completed.push(items[i]);
         }
       }

       var reversedItems = [];
       var order = 1;

       for (var i=completed.length - 1;i>=0;i--){
          var item = completed[i];
          item.order = order;
          order++;
          reversedItems.push(item);
       }

       for (var i=0; i<queue.length;i++){
          var item = queue[i];
          item.order = order;
          order++;
          reversedItems.push(item);
       }

       return reversedItems;
     },

     bid(){
       if(this.time_remaining <= this.auction.snipe_time){
         this.resetTimer(this.auction.snipe_time);
       }
       // this.resetTimer();
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
     },

     // messaging

     //
     sendMessage() {
         axios.post('/messages', {
             user: this.user,
             message: this.newMessage,
             auction: this.auction.id,
             other_user_id: this.messageTo
         }).then(response => {
           this.fetchMessages();
         });
         this.newMessage = ''
     },

     sendMail(email){
       // console.log(email);
       if(email == "everyone"){
         var emails = [];
         if(this.auction.user.id != this.user.id){
           emails.push(this.auction.user.email);
         }
         for(var i=0;i<this.auction.users.length;i++){
           if(this.auction.users[i].id != this.user.id){
             emails.push(this.auction.users[i].email);
           }
         }
         window.location.href = 'mailto:' + emails.join();
       } else {
         window.location.href = 'mailto:' + email;
       }

     },

     // gets all group messages for the auction
     fetchMessages() {
         axios.get('messages/' + this.auction.id).then(response => {
             this.messages = response.data;
         }).then(response => {
           var widget = document.getElementById('chat-widget-body');
           widget.scrollTop = widget.scrollHeight;
         });

     },

     sendPrivateMessage(){
       axios.post('/messages/private', {
           user: this.user,
           message: this.newMessage,
           auction: this.auction.id,
           other_user: this.privateMessage.user.id
       }).then(response => {
         this.fetchMessages();
       });
       this.newMessage = ''
     },

     getPrivateMessage(){

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
        this.getTimeRemaining();
        if(this.time_remaining < 1 && this.auction.manual_next == 0 && this.auction.item.id == this.auction.queue[0].id){
            this.startNextItem();
        }
     },

     toggleStatus(){
       if(this.auction.status.status.in_progress){
         this.pauseStatus();
       } else {
         this.startStatus();
       }
     },

     pauseStatus(){
       this.auction.status.status.label = "Paused";
       this.auction.status.status.in_progress = false;
       this.setStatus(this.auction.status.status);
     },

     startStatus(){
       this.auction.status.status.label = "In Progress";
       this.auction.status.status.in_progress = true;
       this.setStatus(this.auction.status.status);
     },

     setStatus(status){
       axios.post('/auctions/status',{
         auction: this.auction.id,
         in_progress: status.in_progress,
         label: status.label
       }).then(response => {
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


     switchToItem(item_id){

         axios.post('/auctions/item/switch',{
           item_id: item_id,
           auction_id: this.auction.id
         }).then(response => {
           // console.log(response.data);
           // this.getAuctionData(this.auction.id);
           this.updatePool(response.data);
           this.time_remaining = this.auction.bid_timer;
           this.updateClock();
         }).catch(e => {
           console.log(e);
         });

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
       if(this.auction.queue.length > 0){
         axios.post('/auctions/items/next',{
           auction_id: this.auction.id,
           item_id: this.auction.item.id,
           bid_id: this.getCurrentBid()
         }).then(response => {
           // this.getAuctionData(this.auction.id);
           // this.getTimeRemaining();
           this.updatePool(response.data);
           this.time_remaining = this.auction.bid_timer;
           this.updateClock();
         }).catch(e => {
           console.log(e);
         });
       } else {
         this.pauseStatus();
       }

     },

     endAuction(){

     },

     getAuctionData(auction_id,callback){
       axios.get('/auction/data/' + auction_id,{
       }).then(response => {
         this.updatePool(response.data);
         if(this.user.id == this.auction.user.id){
           this.showOwnerControls = true;
         }
        this.fetchMessages();
        if(callback){
          callback();
        }
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
       if( !( auction.items.length > 0 ) ){
         this.auction.items = [];
       }
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

       this.auction.reversedItems = this.reverseItems();
       // console.log(auction);
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
     },

     uploadItemsCsv(){
       this.showImportItemsWarning = false;
       this.csv_items = [];
      this.itemsCsv = this.$refs.itemsCsv.files[0];
      let formData = new FormData();
      formData.append('itemsCsv', this.itemsCsv);
      // if(this.itemsCsv.type == "text/csv"){
        axios.post('/auctions/import-items' ,formData ).then(response => {
          if(response.data == 0){
            this.importItemsWarning = "No data was found in the file. Check the format of your data."
            this.showImportItemsWarning = true;
          } else {
            this.csv_items = response.data;
          }
        }).catch(e => {
          console.log(e);
        });
      // } else {
      //   this.importItemsWarning = "File must be csv."
      //   this.showImportItemsWarning = true;
      // }
     },



     importItems(){
       this.showImportItemsWarning = false;
       if(this.csv_items.length > 0 ){
         axios.post('/auctions/addItem',{
           auction_id: this.auction.id,
           items: this.csv_items
         }).then(response => {
           this.getAuctionData(this.auction.id);
         }).catch(e => {
           console.log(e);
         });
       } else {
         this.importItemsWarning = "No items to import.";
         this.showImportItemsWarning = true;
       }
     }

 },

 mounted() {

   // $('.draggable').draggable();
   // $('.resizable').resizable();

   $('.controlbar').resizable();

   $('.controlbar').draggable(
      {
        snap:".topbar",
        cancel: ".control-item"
      }
   );

       $('.control-container').draggable(
         {
           grid: [5,5],
           stack: ".control-container",
           cancel: ".control",
           cursor: "move"
         }
       );

       $('.previous-bids').draggable(
         {
           grid: [5,5],
           stack: ".control-container",
           cancel: ".control",
           cursor: "move"
         }
       );



         $('.control').draggable(
           {
             containment:".controlbar",
             stack: ".control",
             cancel: ".control-ui",
             cursor: "move"
           }
         );


   $('.widget-container').draggable(
     {
       grid: [5,5],
       handle: ".widget-header",
       cursor: "grab",
       stack: ".widget-container"
     }
   );
   $('.widget-container').resizable();

   $('.sidebar').draggable(
     {
       grid: [5,5],
       stack: ".widget-container",
       handle: "h3"
     }
   );
   $('.bidders-overview').resizable();



   if(document.getElementById('user_id')){
     this.user.id = document.getElementById('user_id').value;
   }
   if(document.getElementById('auction_id')){
     this.getAuctionData(document.getElementById('auction_id').value,function(){
       // $('#items-widget-body').animate({scrollTop: $('#items-widget-body').height() + 100},500);
     });
     setInterval(function(){ this.countDown() }.bind(this),1000);
   }

 },

 created() {
   // let the same message event handle all real-time updates
   Echo.private('chat')
     .listen('MessageSent', (e) => {
       if(e.type == "chat"){
         this.fetchMessages();
         this.notifications.messages['Everyone'] = true;
       } else if (e.type == "private"){
         console.log({test: e});
         this.fetchMessages();
         this.notifications.messages[e.user.id] = true;
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
