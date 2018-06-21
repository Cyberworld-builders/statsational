@extends('layouts.dash')
@section('content')
  {{-- <Auction :auction="{{ $auction }}" :bids="{{ $bids }}" :user="{{ $user }}"></Auction> --}}

  <input id="auction_id" type="hidden" value="{{ $auction->id }}">
  <input id="user" type="hidden" value="{{ $user }}">



  <div class="auction">   <!-- the entire auction room -->
    <nav class="top-bar"> <!-- top bar nav -->
      <div class="row">
      	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-8">
      		Draft Room
          <button class="btn "><span id="timer">@{{ timer }}</span></button>
          Status: In Progress
      	</div>
      	<div class="owner-tools col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-2">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a id="owner-tools" class="nav-link dropdown-toggle" href="/auctions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Commissioner
                </a>
                <div class="dropdown-menu" aria-labelledby="owner-tools">
                  <a class="dropdown-item" href="#" @click="startNextItem">Start Next Item</a>
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
              <p>@{{ auction.item.name }}</p>
            </div>

            <div id="current_bid" class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
              <p v-if="auction.item.bids.length" >Current Bid: $ @{{ Number(auction.item.bids[0].amount) }}</p>
              <p v-else>No bids yet.</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
              <p v-if="auction.item.bids.length > 0 && bidders[auction.item.bids[0].user_id]" id="hightest_bidder"> @{{ bidders[auction.item.bids[0].user_id].name }}</p>
            </div>

          </div>
        </div>
        <div class="bidding-controls controls col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-6">
          <div v-if="auction.queue && auction.queue.length">
            <small>Quick Bid</small><button @click="bidMinimum" class="btn bid-button">Bid $ @{{ getMinimumBid() }}</button>
            <small>Mannual Bid</small>
            <button @click="lowerBid" class="btn bid-button"><i class="fa fa-minus"></i></button>
            <input class="" type="number" v-model="user.bid.amount">
            <button @click="raiseBid" class="btn bid-button"><i class="fa fa-plus"></i></button>
            <button @click="bid" class="btn bid-button"><strong><i class="fa fa-angle-left"></i> &nbspBid</strong></button>
          </div>

        </div>
      </div>
    </nav> <!-- end bidding controls bar -->
    <div class="row justify-content-center"> <!-- the main content area     -->
      <div class="col-md-12 auction-room"> <!-- full width container for the auction room    -->
          <div class="row money-spent">
          	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-12">
          		<span>Money Spent ($)</span> &nbsp
              <span v-if="bidders[user.id]" class="spent">@{{ bidders[user.id].spend }}</span>
              <span v-else>0</span>
          	</div>
          </div>
          <div class="row ">
          	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-10"> <!-- main widget area     -->
              <div class="row">
              	<div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-8">

                  <div class="row">
                    <div class="widget-card">
                      <h3>Items</h3>
                      <div v-if="auction.queue" class="widget-body scrollable col-md-12">
                        <ul v-for="(item,index) in auction.queue">
                          <li>
                            <span>@{{ auction.queue[index].name }}</span>
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
                      <div class="row">
                      	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      		<h3>Owned</h3>
                      	</div>
                      	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                      		<input class="form-control" v-model="selectedBidder.name" />
                      	</div>
                      </div>


                      <div v-if="selectedBidder.id && selectedBidder.owned_items" class="widget-body scrollable col-md-12">
                        <ul v-for="(owned,index) in selectedBidder.owned_items">
                          <li>
                            <span>@{{ owned.item.name }} ( $ @{{ Math.round(owned.winning_bid.amount) }} )</span>
                          </li>
                          <hr />
                        </ul>
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

                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                      <div class="panel-body">
                                        <ul class="chat">
                                            <li class="left clearfix" v-for="message in messages">
                                                <div class="chat-body clearfix">
                                                    <div class="header">
                                                        <strong class="primary-font">
                                                            @{{ message.user.name }}
                                                        </strong>
                                                        <span>@{{ message.created_at }}</span>
                                                    </div>
                                                    <p>
                                                        @{{ message.message }}
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
                                              <option v-for="bidder in messagers" >@{{ bidder.name }}</option>
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
                  <a href="#" @click="updateSelectedBidder(bidder.id)">
                    <div class="bidder-card">
                      <p>@{{ bidder.name }} <span v-if="bidders[index].active" class="">@{{ bidders[index].active }}</span> </p>
                      <span>Bal: $@{{ bidder.spend }}</span>&nbsp&nbsp<span>Avg Spend: $@{{ bidder.average_spend }}</span>
                      <p class="players-needed" v-if="bidder.item_count > 0">Items Owned: @{{ bidder.item_count }}</p>
                    </div>
                  </a>

                </div>
              </div>
          	</div> <!-- end right sidebar -->
          </div>

        </div> <!-- end full width container -->
    </div> <!-- end main content area     -->
  </div> <!-- end auction room -->

@endsection
