@extends('layouts.dash')
@section('content')
  {{-- <Auction :auction="{{ $auction }}" :bids="{{ $bids }}" :user="{{ $user }}"></Auction> --}}

  <input id="auction_id" type="hidden" value="{{ $auction->id }}">
  <input id="user_id" type="hidden" value="{{ $user->id }}">

  <div class="auction-new">
    <div class="topbar ">
      <ul class="topbar-container ">
        <li class="topbar-item col-12 col-md-2 col-lg-2 col-xl-1" >
          <div class="logo-container">
            <a href="/home">
                <img class="logo px-1" src="/images/logo.png" />
            </a>
            <a  v-if="showOwnerControls" id="mobile-owner-tools" href="#" class=" d-md-none pull-right mr-3" role="button" data-toggle="mobile-dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
              <i class="fa fa-bars"></i>
            </a>

          </div>
        </li>
        <li class="topbar-item col-12 col-md-4 col-lg-4 col-xl-5 text-left" >
            <span class="ml-3" v-if="auction.status">
              <span v-if="auction.status.status.in_progress == true">@{{ auction.status.status.label }}</span>
              <span v-else class="blinking">@{{ auction.status.status.label }}</span>
            </span>
            : <strong>@{{ auction.name }}</strong>
        </li>
        <li class="topbar-item d-none d-md-block col-md-4 col-lg-4 col-xl-5 text-left" >
          <span class="nav-item ml-3" v-if="bidders[user.id]">
            Logged In: <strong>@{{ bidders[user.id].name }}</strong>
          </span>
        </li>
        <li class="topbar-item d-none d-md-block col-md-2  col-lg-2 col-xl-1" >
          <div class="info-container mr-3">
              <ul class="icons navbar-nav">
                <li v-if="showOwnerControls" class="nav-item dropdown ">
                  <a class="pull-right ml-3" @click="toggleStatus" href="#">
                    <span><i class="fa fa-power-off"></i></span>
                  </a>
                    <a id="owner-tools" class="nav-item dropdown-toggle pull-right" href="/auctions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <i class="fa fa-cog"></i>
                    </a>
                    <div class="dropdown-wrapper">
                      <div class="dropdown-menu " aria-labelledby="owner-tools">
                        <a class="dropdown-item" href="#" @click="startNextItem">Start Next Item</a>
                        <a class="dropdown-item" href="#" @click="resetTimer(auction.bid_timer)">Restart Clock</a>
                        <a class="dropdown-item" href="#" @click="endAuction">End Auction</a>
                        <a class="dropdown-item" href="/auction/summary/{{ $auction->id }}" >Summary</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#add-item"><i class="fa fa-plus"></i> Add Item</a>
                      </div>
                    </div>
                </li>
              </ul>
            </div>
        </li>
      </ul>
    </div> <!-- end top bar -->

    <div class="controlbar resizable">
      <div class="row ">
        <div class="col-12 col-md-6 offset-lg-1 col-lg-5 offset-xl-2 col-xl-4"> <!-- bidding controls -->
          <div class="row text-center">
            <div class="control-container-wrapper">
              <div class="control-container container-fluid">
                <table class="table">
                  <tr class="control-wrapper">
                    <td colspan="2" ><span id="timer" class="control ">@{{ timer }}</span></td>
                  </tr>
                  <tr>
                    <td class="text-right">Current Item on the Block:</td>
                    <th class="text-left"><span>@{{ auction.item.name }}</span></th>
                  </tr>
                  <tr>
                    <td class="text-right">Current Bid Winner:</td>
                    <th class="text-left" v-if="auction.item.bids.length > 0 && bidders[auction.item.bids[0].user_id]" id="highest_bidder">
                      <span id="current_bid" >
                        @{{ bidders[auction.item.bids[0].user_id].name }} ($ @{{  Math.round(auction.item.bids[0].amount) }})
                      </span>
                    </th>
                    <td class="text-left" v-else>No bids yet.</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="control-item">
                      <div class="control-wrapper">
                        <div class="control">
                          <div class=" bidding-controls" v-if="auction.queue && auction.queue.length">
                            <button @click="lowerBid" class="btn bid-button control-ui"><i class="fa fa-minus"></i></button>
                            <input @focus="$event.target.select()" id="manualBid" type="number" v-model="bid_amount" class="control-ui">
                            <button id="bidButton" @click="bid" class="btn bid-button control-ui">Bid</button>
                            <button @click="raiseBid" class="btn bid-button control-ui"><i class="fa fa-plus"></i></button>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div> <!-- end bidding controls -->

        <div class="previous-bids offset-1 col-11  offset-md-0 col-md-6 col-lg-5 col-xl-4"> <!-- previous-bids -->

              <div class="bid-list m-2" v-if="auction.item.bids.length > 0 && bidders[auction.item.bids[0].user_id]" id="highest_bidder">
                <table class="table text-center ">
                  <tr>
                    <th colspan="2">Previous Bids</th>
                  </tr>
                  <tr v-for="(bid,index) in auction.item.bids">
                    <th >
                     $ @{{  Math.round(auction.item.bids[index].amount) }}
                    </td>
                    <td>
                      @{{ bidders[auction.item.bids[index].user_id].name }}
                    </td>
                  </tr>
                </table>

              </div>
              <div v-else>No bids yet</div>

        </div> <!-- end previous-bids -->

      </div>

      <b-alert class="minimum-bid-warning" variant="danger"
               dismissible
               :show="showMinimumBidWarning"
               @dismissed="showMinimumBidWarning=false">
      Your bid cannot be lower than the minimum bid!
      </b-alert>

    </div>

    <div class="widgets container-fluid">

      <div class="row">
        <div class="main-content col-12 col-md-9"> <!-- main widget content area -->
          <div class="row">

            <div class="items widget col-12"> <!-- items widget -->
              <div id="items-widget-body" class="widget-container m-1"> <!-- widget-container -->
                <div class="widget-header p-3">
                  <h3>Items</h3>
                </div>
                <div class="widget-body p-3">
                  <input id="showCompletedItems" type="checkbox" v-model="showCompletedItems" name="" value="">
                  <label for="showCompletedItems">
                    <span>Show Completed Items</span>
                  </label>
                  <div v-if="auction.queue" class="widget-body col-md-12">

                    <table class="table">
                      <thead>
                        <th>&nbsp</th><th>Item</th><th v-if="showCompletedItems">Winning Bid</th><th v-if="showCompletedItems">Bidder</th>
                      </thead>

                        <tbody>
                          <tr v-for="(item,index) in auction.reversedItems">
                              <td v-if="isActive(item.id) || showCompletedItems">@{{ index + 1 }}</td>
                              <td v-if="isActive(item.id)">@{{ auction.reversedItems[index].name }}</td>
                              <td v-if="!isActive(item.id) && showCompletedItems" class="disabled">@{{ auction.reversedItems[index].name }}</td>
                              <td v-if="!isActive(item.id) && item.bids.length > 0 && showCompletedItems && (typeof auction.reversedItems[index].bids[0] != 'undefined'  )" >$ @{{ auction.reversedItems[index].bids[0].amount }} </td>
                              <td v-if="( isActive(item.id) || ( !(item.bids.length > 0) ) ) && showCompletedItems "> - </td>
                              <td v-if="!isActive(item.id) && item.bids.length > 0 && showCompletedItems" > @{{ bidders[auction.reversedItems[index].bids[0].user_id].name }} </td>
                              <td v-if="( isActive(item.id) || ( !(item.bids.length > 0) ) ) && showCompletedItems "> - </td>
                              <td v-if="(auction.user.id == user.id) && ( isActive(item.id) || showCompletedItems )"><a href="#" @click="switchToItem(item.id)">Go to Item</a></td>
                          </tr>
                        </tbody>






                    </table>
                    </ul>
                  </div>
                </div>
              </div> <!-- end widget-container -->
            </div> <!-- end items widget -->

            <div id="add-item" class="modal" tabindex="-1" role="dialog" aria-labelledby="add-item-label"> <!-- add item modal -->
              <div class="modal-dialog modal-lg widget" role="document">
                <div class="modal-content widget-container">
                  <div class="modal-header widget-header">
                    <h3 class="modal-title" id="add-item-label">Add Item</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="widget-body p-2">
                    <form id="addItem" @submit.prevent="addItem" role="form">
                      <div class="form-group">
                        <label for="name">
                          Item Name
                        </label>
                        <input v-model="name" type="text" class="form-control" id="itemName" />
                        <b-btn class="mt-3 add-item-button" block @click="addItem">Add Item</b-btn>
                        <hr>
                        <label for="exampleFormControlFile1">Import from CSV</label>
                        <input type="file" class="form-control-file" id="itemsCsv" ref="itemsCsv" @change="uploadItemsCsv">
                        <div class="container m-2">
                          <div class="row">
                            <div class="col-12">
                              <table v-if="csv_items.length > 0" class="table">
                                <thead>
                                  <tr>
                                    <th>Item Name</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr v-for="item in csv_items">
                                    <td>@{{ item.name }}</td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <b-alert class="minimum-bid-warning" variant="danger"
                                 dismissible
                                 fade
                                 :show="showImportItemsWarning"
                                 @dismissed="showImportItemsWarning=false">
                            <span>@{{ importItemsWarning }}</span>
                        </b-alert>
                        <b-btn class="mt-3 add-item-button" block @click="importItems">Import Items</b-btn>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div> <!-- end add items modal -->


            <div class="chat-widget widget col-12"> <!-- chat-widget -->
              <div class="widget-container mx-1">
                <div class="widget-header p-3">
                  <h3>Chat <span v-if="this.messageTo != 'Everyone'">( @{{ bidders[messageTo].name }} )</span> </h3>

                </div>
                <div class="widget-body p-3">
                  <div id="chat-widget-body" class="panel-body">
                    <div class="row">
                      <div class="col-9">
                        <ul class="chat">
                            <li class="left clearfix" v-for="message in messages[this.messageTo]">
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
                      </div>
                      <div class="col-3">

                        <ul style="list-style-type: none;">
                          <li>
                            <a href="#" @click="messageTo = 'Everyone'; notifications.messages['Everyone'] = false;">Everyone</a>
                            <i v-if="notifications.messages['Everyone'] === true">*</i>
                          </li>
                        </ul>
                        <ul style="list-style-type: none;" v-for="(bidder,index) in bidders">
                          <li>
                            <a href="#" @click="messageTo = bidder.id; notifications.messages[bidder.id] = false;">@{{ bidder.name }}</a> 
                            <i v-if="notifications.messages[bidder.id] === true">*</i>
                          </li>
                        </ul>

                      </div>
                    </div>

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
                </div>
              </div>
            </div> <!-- end chat-widget -->

            <div id="private-chat" class="modal" tabindex="-1" role="dialog" aria-labelledby="private-chat" data-backdrop="false"> <!-- add item modal -->
              <div class="modal-dialog widget" role="document">
                <div class="modal-content widget-container">
                  <div class="modal-header widget-header">
                    <h3 class="modal-title" id="private-chat">Private Chat</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="widget-body p-2">

                  </div>
                </div>
              </div>
            </div> <!-- end add items modal -->


          </div>
        </div> <!-- end main widget content area -->

        <div class="sidebar col-12 col-md-3 widget"> <!-- sidebar for bidders overview -->


          <b-alert fade class="bidders-overview-details widget-card" variant="light"
             dismissible
             :show="showBiddersOverviewDetail"
             @dismissed="showBiddersOverviewDetail=false">
             <div class=" ">
               <div class="row">

                 <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                   @{{ selectedBidder.name }}
                 </div>


               </div>
               <div class="clearfix">
                 <br />
               </div>
               <div v-if="selectedBidder.id && selectedBidder.owned_items" class="widget-body scrollable col-md-12">
                 <table class="table">
                   <thead>
                     <tr>
                       <th scope="col">#</th>
                       <th scope="col">Item</th>
                       <th scope="col" class="text-right">Cost</th>
                     </tr>
                     <tbody>
                       <tr v-for="(owned,index) in bidders[selectedBidder.id].owned_items">
                         <th scope="row">@{{ index + 1 }}</th>
                         <td>@{{ owned.item.name }}</td>
                         <td class="text-right" >$ @{{ Math.round(owned.winning_bid.amount) }}</td>
                       </tr>
                     </tbody>
                   </thead>
                 </table>
               </div>
             </div>
          </b-alert>

          <div class="bidders-overview my-1">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <h3>Bidders Overview</h3>
              </div>

            </div>
              <table class="table">
                <thead>
                  <tr class="bidder-card ">
                    <th scope="col">Name</th>
                    <th scope="col" class="text-right">Spent</th>

                  </tr>
                  <tbody>
                    <tr v-bind:class="[(bidder.id == user.id) ? 'isUser' : '']" v-for="(bidder,index) in sorted_bidders"  @click="updateSelectedBidder(bidder.id)" class="bidder-card ">
                      <td ><strong>@{{ bidder.name }} </strong></td>
                      <td class="text-right"><span>$@{{ bidder.spend }}</span></td>
                    </tr>
                  </tbody>
                </thead>
              </table>
            </div>
        </div> <!-- end bidders overview sidebar -->

      </div>



    </div> <!-- end widgets area -->

    <auction></auction>


  </div> <!-- end auction -->


@endsection
