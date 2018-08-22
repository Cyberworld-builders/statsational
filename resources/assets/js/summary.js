
require('./bootstrap');


var Vue = require('vue');

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import axios from 'axios'

import 'lodash/lodash.js'

window.$ = window.jQuery = require('jquery');

import 'jquery-ui/ui/core.js';
import 'jquery-ui/ui/widget.js';
import 'jquery-ui/ui/data.js';
import 'jquery-ui/ui/widgets/resizable.js';
import 'jquery-ui/ui/widgets/draggable.js';
import 'jquery-ui/ui/widgets/mouse.js';

new Vue({
 el: '#app',
 data: {
   new_bid: {
     item_id: "",
     bid_amount: "",
     user_id: "",
     auction_id: ""
   },
   query: [],
   sortBy: "item",
   desc: true,
   auction: {
     name: '',
     participants: {

     },
     item_refs: {

     },
     summary: {
       winning_bids: [],
       winners: []
     },
     bid_log: []

   },
   user: {}
 },
 methods: {

   calculateBidderStats(){
     // convert bidders ref to array of bidders so we can iterate over them
     var bidders = Object.values(this.bidders);

     // loop through the new bidders array and build a spend_values object with the bidder id as a key and the spend amount as the value
     var spend_values = {};
     for(var i=0;i<bidders.length;i++){
       spend_values[bidders[i].id] = bidders[i].spend;
     }

     // loop through the spend values object and build a sorted values array by pushing arrays of [ bidder_id, spend ] onto it
     var sorted_values = [];
     for (var bidder in spend_values){
       sorted_values.push([bidder,spend_values[bidder]]);
     }

     // this is where the sorting happens
     sorted_values.sort(function(a,b){
       return b[1] - a[1];
     });

     // iterate over sorted values to build the sorted bidders array by pushing
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

   sort(){

     var winning_bids_ref = {};

     var rows = [];

     var item_values = [];
     var winner_values = [];
     var amount_values = [];

     var winning_bids = this.auction.summary.winning_bids;
     for(var i=0;i<winning_bids.length;i++){
       var bid = winning_bids[i];
       rows.push(bid);
       item_values.push(bid.name);
       winner_values.push(bid.winner);
       amount_values.push(bid.amount);
       winning_bids_ref[i] = winning_bids[i];
     }

     if(this.sortBy == "item"){
       var item_values = {};
       for(var i=0;i<winning_bids.length;i++){
          var value = winning_bids[i].name;

          if(typeof value != "undefined"){
            item_values[i] = value;
          } else {
            item_values[i] = "Unknown";
          }

       }
       var sorted_values = [];
       for (var key in winning_bids_ref){
         sorted_values.push([key,item_values[key]]);
       }
       sorted_values.sort(function(a,b){
         return a[1].localeCompare(b[1]);
       });
       var sorted_winning_bids = [];
       for(var i=0;i<sorted_values.length;i++){
         var winning_bid = winning_bids_ref[sorted_values[i][0]];
         sorted_winning_bids.push( winning_bid );
       }
     }

     if(this.sortBy == "cost"){
       var amount_values = {};
       for(var i=0;i<winning_bids.length;i++){
          var value = winning_bids[i].amount;

          if(typeof value != "undefined"){
            amount_values[i] = value;
          } else {
            amount_values[i] = 0;
          }

       }
       var sorted_values = [];
       for (var key in winning_bids_ref){
         sorted_values.push([key,amount_values[key]]);
       }
       sorted_values.sort(function(a,b){
         return b[1] - a[1];
       });
       var sorted_winning_bids = [];
       for(var i=0;i<sorted_values.length;i++){
         var winning_bid = winning_bids_ref[sorted_values[i][0]];
         sorted_winning_bids.push( winning_bid );
       }
     }




     if(this.sortBy == "winner"){
       var winner_values = {};
       for(var i=0;i<winning_bids.length;i++){
          var value = winning_bids[i].winner;

          if(typeof value != "undefined"){
            winner_values[i] = value;
          } else {
            winner_values[i] = "N/A";
          }

       }
       var sorted_values = [];
       for (var key in winning_bids_ref){
         sorted_values.push([key,winner_values[key]]);
       }
       sorted_values.sort(function(a,b){
         return a[1].localeCompare(b[1]);
       });
       var sorted_winning_bids = [];
       for(var i=0;i<sorted_values.length;i++){
         var winning_bid = winning_bids_ref[sorted_values[i][0]];
         sorted_winning_bids.push( winning_bid );
       }
     }

     this.query = sorted_winning_bids;

   },
   sortByItem(){
     if(this.sortBy == "item"){
       this.desc = !this.desc;
     }
     this.sortBy = "item";
     this.sort();
   },
   sortByWinner(){
     if(this.sortBy == "winner"){
       this.desc = !this.desc;
     }
     this.sortBy = "winner";
     this.sort();
   },
   sortByCost(){
     if(this.sortBy == "cost"){
       this.desc = !this.desc;
     }
     this.sortBy = "cost";
     this.sort();
   },
   getAuctionData(auction_id){
     this.axiosGet('/auction/data',auction_id,this.calculateSummary);
   },

   calculateSummary(auction){
     this.auction = auction;
     this.auction.summary = {};
     this.new_bid.auction_id = auction.id;
     this.getParticipants();
     this.getWinningBids();
     this.getItemRefs();
     this.prepareBidLog();
     // this.getWinners();
     this.sort();
     // console.log(this.auction.summary.winners);
   },
   prepareBidLog(){
     var bid_log = [];
     var auction = this.auction;
     // console.log(auction.items[0]);
     for(var i=0;i<auction.items.length;i++){
       var item = auction.items[i];
       for(var j=0;j<item.bids.length;j++){
         var bid = {};
         bid.id = item.bids[j].id;
         bid.time = item.bids[j].updated_at;
         bid.item = item.name;
         bid.amount = item.bids[j].amount;
         bid.participant = this.auction.participants[item.bids[j].user_id].name;
         // console.log(item.bids);
         bid_log.push(bid);
       }
     }
     console.log(bid_log);
     this.auction.bid_log = bid_log;
   },
   getParticipants(){
     var auction = this.auction;
     var participants = {};
     participants[auction.user.id] = auction.user;
     participants[auction.user.id].items = [];
// console.log(participants);
     for(var i=0;i<auction.users.length;i++){
       var user = auction.users[i];
       user.items = [];
       participants[user.id] = user;
     }
     this.auction.participants = participants;
   },
   getItemRefs(){
     var item_refs = {};
     var items = this.auction.items;
     for(var i=0;i<items.length;i++){
       item_refs[items[i].id] = items[i];
     }
     this.auction.item_refs = item_refs;
   },
   getWinningBids(){
     var winning_bids = [];
     var items = this.auction.items;
     this.auction.summary.winning_bids = winning_bids;
     for(var i=0;i<items.length;i++){
        winning_bids.push(this.getWinningBid(items[i]));
     }
     this.auction.summary.winning_bids = winning_bids;
     // console.log(winning_bids);
   },
   getWinningBid(item){
     var winning_bid = {
       amount: 0,
       name: item.name,
       winner: "-"
     };
     for(var i=0;i<item.bids.length;i++){
       var bid = item.bids[i];
       if(bid.amount > winning_bid.amount){
         winning_bid.amount = bid.amount;
         winning_bid.winner = this.auction.participants[bid.user_id].name;
       }
     }
     return winning_bid;
   },
   getWinners(){
     this.auction.summary.winners = [];
     var winners = this.auction.participants;
     var winning_bids = this.auction.summary.winning_bids;
     for(var i=0;i<winning_bids.length;i++){
       var bid = winning_bids[i];

       if(bid.amount > 0){
         var item = {
           name: this.auction.item_refs[bid.item_id].name,
           amount: bid.amount
         };
         winners[bid.user_id].items.push(item);
       }

     }
     // console.log(this.auction.summary.winners);
     if(winners[this.auction.user.id].items.length > 0){
       this.auction.summary.winners.push(winners[this.auction.user.id]);
     }
     for(var i=0;i<this.auction.users.length;i++){
       if(winners[this.auction.users[i].id].items.length > 0){
         this.auction.summary.winners.push(winners[this.auction.users[i].id]);
       }
     }
   },

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


   getWinner(){

   },
   trashBid(bid_id){
     this.axiosGet('/auctions/remove-bid',bid_id,this.getAuctionData(this.auction.id));
   },
   saveBid(bid_id, amount){
     this.axiosPost('/auctions/remove-bid',{
       bid_id: bid_id,
       amount: amount
     },this.getAuctionData(this.auction.id));
   },
   addBid(){
     this.axiosPost('/auctions/bid',{
       auction_id: this.new_bid.auction_id,
       item_id: this.new_bid.item_id,
       user_id: this.new_bid.user_id,
       bid_amount: this.new_bid.bid_amount
     },this.getAuctionData(this.auction.id));
   }

 },
 mounted() {
   if( document.getElementById('auction_id') ){
     this.getAuctionData(document.getElementById('auction_id').value);
     this.user.id = document.getElementById('user_id').value;

   }
 }


});
