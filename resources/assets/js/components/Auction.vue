<template>


  <div class="auction">   <!-- the entire auction room -->
    <nav class="top-bar"> <!-- top bar nav -->
      <div class="row">
      	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-8">
      		Draft Room
          <button class="btn "><span id="timer">{{ timer }}</span></button>
          Status: In Progress
      	</div>
      	<div class="owner-tools col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-2">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a id="owner-tools" class="nav-link dropdown-toggle" href="/auctions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Commissioner
                </a>
                <div class="dropdown-menu" aria-labelledby="owner-tools">
                  <a class="dropdown-item" href="#" >Start Next Item</a>
                  <a class="dropdown-item" href="#" @click="resetTimer(30)">Restart Clock</a>
                  <a class="dropdown-item" href="#" >Undo Last Bid</a>
                  <a class="dropdown-item" href="#" >End Auction</a>
                  <a class="dropdown-item" href="#" >Reload App</a>
                  <a class="dropdown-item" href="#" @click="showModal"><i class="fa fa-plus"></i> Add Item</a>
                </div>
            </li>
          </ul>
      	</div>
        <div class="owner-tools col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-2">
          <div class="row icons">
          	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
          		<a href="#"><span><i class="fa fa-bell"></i></span></a>
          	</div>
          	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
          		<a href="#"><span><i class="fa fa-flag"></i></span></a>
          	</div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
          		<a href="#"><span><i class="fa fa-power-off"></i></span></a>
          	</div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
          		<a href="#"><span><i class="fa fa-cog"></i></span></a>
          	</div>
          </div>
      	</div>
      </div>
    </nav> <!-- end top bar nav -->
    <nav class="bidding navbar-dash navbar-expand-md navbar-light"> <!-- bidding controls bar     -->
      <div class="row">
        <div class="navbar-home col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-2">
          <a class="navbar-brand" href="/home">
              <img class="logo-small" src="/images/logo.png" />
          </a>
        </div>
        <div class="bidding-controls col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
              <p>On the Block</p>
              <p>{{ current_item.name }}</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
              <p id="current_bid">Current Bid: $ {{ current_item.high_bid.amount }}</p>

            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
              <p>{{ high_bidder }}</p>
            </div>
          </div>
        </div>
        <div class="bidding-controls controls col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-6">
          <small>Quick Bid</small><button @click="minimumBid" class="btn bid-button">Bid ${{ minimum_bid }}</button>
          <small>Mannual Bid</small>
          <button @click="lowerBid" class="btn bid-button"><i class="fa fa-minus"></i></button>
          <input class="" type="number" v-model="bid_amount">
          <button @click="raiseBid" class="btn bid-button"><i class="fa fa-plus"></i></button>
          <button @click="bid" class="btn bid-button"><strong><i class="fa fa-angle-left"></i> &nbspBid</strong></button>
        </div>
      </div>
    </nav> <!-- end bidding controls bar -->
    <div class="row justify-content-center"> <!-- the main content area     -->
      <div class="col-md-12 auction-room"> <!-- full width container for the auction room    -->
          <div class="row money-spent">
          	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-12">
          		<span>Money Spent ($)</span> &nbsp<span class="spent">{{ 200 }}</span>
          	</div>
          </div>
          <div class="row ">
          	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-10"> <!-- main widget area     -->
              <div class="row">
              	<div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-8">

                  <div class="row">
                    <div class="widget-card">
                      <h3>Items</h3>
                      <div class="widget-body scrollable col-md-12">
                        <ul v-for="(item,index) in auction.queue">
                          <li>
                            <span v-if="index != 0">{{ auction.queue[index].name }}</span>
                          </li>
                          <hr />
                        </ul>
                      </div>
                    </div>

                    <div class="col-xs-3 col-md-2">
                      <b-modal ref="myModalRef" hide-footer title="Add Item">
                        <form id="addItem" @submit.prevent="addItem" role="form">
                          <div class="form-group">
                            <label for="name">
                              Item Name
                            </label>
                            <input v-model="name" type="text" class="form-control" id="itemName" />
                          </div>
                          <b-btn class="mt-3" block @click="addItem">Add</b-btn>
                        </form>
                      </b-modal>
                    </div>

                  </div>

              	</div>

              	<div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                  <div class="row">

                    <div class="widget-card ">
                      <h3>Owned</h3>
                      <div class="widget-body scrollable col-md-12">

                      </div>
                    </div>

                  </div>
              	</div>

              </div>

              <div class="row ">

                <div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                  <div class="row">

                    <div class="widget-card ">
                      <h3>Queue</h3>
                      <div class="widget-body scrollable col-md-12">

                      </div>
                    </div>

                  </div>
              	</div>

                <div class="col-sm-12 col-md-12 col-lg-7 col-xl-8">
                  <div class="widget-card chat-widget">
                      <h3>Chat</h3>
                      <div class="widget-body">
                          <div class="col-md-12">
                                <!-- <chat :auction="{{ $auction }}" :user="{{ Auth::user() }}"></chat> -->
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                      <div class="panel-body">
                                        <ul class="chat">
                                            <li class="left clearfix" v-for="message in messages">
                                                <div class="chat-body clearfix">
                                                    <div class="header">
                                                        <strong class="primary-font">
                                                            {{ message.user.name }}
                                                        </strong>
                                                        <span>{{ message.created_at }}</span>
                                                    </div>
                                                    <p>
                                                        {{ message.message }}
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                          <div id="scrollToNewMessage"></div>
                                      </div>
                                      <br />
                                      <div class="row">
                                        <div  class="input-group">
                                            <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">Send</button>
                                            </span>

                                        </div>

                                      </div>
                                      <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-8">
                                          <div class="input-group">
                                            <label for="messageTo"><span>To (private)</span></label>
                                            <select id="messageTo" class="form-control input-sm" v-model="messageTo">
                                              <option v-for="bidder in messagers" >{{ bidder.name }}</option>
                                            </select>
                                          </div>
                                        </div>


                                      </div>
                                    </div>

                                  </div>
                          </div>
                      </div>
                  </div>
                </div>
              </div>


          	</div> <!-- end main widget area -->
          	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-2"> <!-- right sidebar -->
              <div class="bidders-overview">
                <div class="row">
                	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-8">
                		<h3>Bidders Overview</h3>
                	</div>
                </div>
                <div v-for="(bidder,index) in bidders" >
                  <div class="bidder-card">
                    <p>{{ bidder.name }} <span v-if="bidders[index].active" class="">{{ bidders[index].active }}</span> </p>
                    <span>Bal: ${{ 200 }}</span>&nbsp&nbsp<span>Max Bid: ${{ 193 }}</span>
                    <p class="players-needed">Players needed: {{ 8 }}</p>

                  </div>
                </div>
              </div>
          	</div> <!-- end right sidebar -->
          </div>

        </div> <!-- end full width container -->
    </div> <!-- end main content area     -->
  </div> <!-- end auction room -->

</template>

<script>
    import Countdown from 'vuejs-countdown'
    import axios from 'axios'
    import moment from 'moment'
    export default {
      props: ['auctionAction','auction','bids','user'],
      data(){
        return {
          bid_amount: 0,
          item_id: 0,
          minimum_bid: 1,
          high_bid: {},
          high_bidder: "",
          newMessage: '',
          messagers: [{ name: "Everyone" }],
          messageTo: "Everyone",
          messages: [],
          bidders: [],
          name: "",
          modalShow: false,
          imActive: true,
          time_remaining: "30",
          timer: "0:30",
          current_item: {},
          new_bid: {}
        }
      },
      methods: {

        // bidding

        bid(){
          axios.post('/auctions/bid',{
            auction_id: this.auction.id,
            bid_amount: this.bid_amount,
            item_id: this.current_item.id
          }).then(function(response){
            this.updateBid(response.data.amount);
            this.resetTimer(30);
            this.high_bidder = this.user.name;
          }.bind(this)).catch(e => {
            console.log(e);
          });
        },

        updateBid(amount){
          this.high_bid = amount;
          this.minimum_bid = this.high_bid + 1;
          this.bid_amount = this.minimum_bid;
        },
        getBids(){

        },
        lowerBid(){
          var new_bid = Number(this.bid_amount) - 1;
          if(new_bid >= this.minimum_bid){
            this.bid_amount = new_bid;
          }
        },
        raiseBid(){
          var new_bid = Number(this.bid_amount) + 1;
          if(new_bid >= this.minimum_bid){
            this.bid_amount = new_bid;
          }
        },
        minimumBid(){
          this.bid_amount = this.minimum_bid;
          this.bid();
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
          }).then(function(response){
            items = response.data;
            location.reload();

          }).catch(e => {
            // console.log(e);
          });
          this.auction.items = items;
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
              // console.log(e);
            });
        },
        updateClock(){
          this.timer = moment().startOf('day')
            .seconds(this.time_remaining)
            .format('m:ss');
        },

        startNextItem(){
          /*
              1. remove from queue array
              2. update the database
              3. broadcast the change
              4. hear the broadcast

          */
          axios.post('/auctions/items/next',{
            auction_id: this.auction.id,
            item_id: this.current_item.id,
            user_id: 0
          }).then(response => {
            this.time_remaining = 30;
            this.updateClock();
          }).catch(e => {
            // console.log(e);
          });
        }

      },

      created() {

        this.fetchMessages();

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
              this.updateBid(Math.round(e.message.amount));
              this.high_bidder = e.user.name;
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
            }
          });

      },

      mounted() {

        this.current_item = this.auction.queue[0];
        this.current_item.bids = [];
        this.current_item.high_bid = { amount: 0 };
        


          // bidding controls
          if(this.bids.length > 0){
            for(var i=0;i<this.bids.length;i++){
              if(this.bids[i].item_id == this.current_item.id){
                this.current_item.bids.push(this.bids[i]);
                if(this.bids[i].amount > this.current_item.high_bid.amount){
                  this.current_item.high_bid = this.bids[i];
                }
              }
            }
            this.high_bid = Number(this.bids[0].amount);
          }
          console.log(this.current_item);
          this.minimum_bid = this.high_bid + 1;
          this.bid_amount = this.minimum_bid;

          // bidders  overview
          this.bidders.push(this.auction.user);
          for(var i=0;i<this.auction.users.length;i++){
            this.bidders.push(this.auction.users[i]);
            this.messagers.push(this.auction.users[i]);

          }
          setInterval(function(){ this.countDown() }.bind(this),1000);


      }

    }
</script>
