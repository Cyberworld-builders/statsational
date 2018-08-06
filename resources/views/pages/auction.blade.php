@extends('layouts.dash')
@section('content')
  {{-- <Auction :auction="{{ $auction }}" :bids="{{ $bids }}" :user="{{ $user }}"></Auction> --}}

  <input id="auction_id" type="hidden" value="{{ $auction->id }}">
  <input id="user_id" type="hidden" value="{{ $user->id }}">


  <div class="auction-new">

    <div class="topbar ">
      <ul class="topbar-container">
        <li class="topbar-item col-12 col-md-2 col-xl-1" >
          <div class="logo-container">
            <a href="/home">
                <img class="logo" src="/images/logo.png" />
            </a>
          </div>
        </li>
        <li class="topbar-item col-11 col-md-9 col-lg-7 col-xl-7" >
          <div class="info-container row">
            <div class="col-6">
              <span v-if="auction.status">
                <span v-if="auction.status.status.in_progress == true">@{{ auction.status.status.label }}</span>
                <span v-else class="blinking">@{{ auction.status.status.label }}</span>
              </span>
              : <strong>@{{ auction.name }}</strong>
            </div>
            <div class="col-6">
              <span v-if="bidders[user.id]">Logged In: <strong>@{{ bidders[user.id].name }}</strong></span>
            </div>



          </div>
        </li>
        <li class="topbar-item col-1 col-md-1 d-lg-none" >
          <div class="info-container">
            <a href="#">
              <i class="fa fa-bars"></i>
            </a>

          </div>
        </li>
        <li class="topbar-item d-none d-lg-block col-lg-3" >
          <div class="info-container">
            <a href="#">
              testtttt
            </a>

          </div>
        </li>

      </ul>
    </div>

    <div class="controlbar container-fluid ">
      <div class="row">

        <div class=" col-12 col-md-6">
          <div class="row text-center">
            <div class="control-container-wrapper">
              <div class="control-container container-fluid">

                <div class="row">
                  <div class="col-12 control-item">
                    <div class="row text-center">
                      <div class="control-wrapper">
                        <div id="timer" class="control">
                          @{{ timer }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 control-item">
                    <div class="row">
                      <div class="control-wrapper">
                        <div class="control">
                          On the Block: @{{ auction.item.name }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 control-item">
                    <div class="row">
                      <div class="control-wrapper">
                        <div class="control">
                          Highest Bidder:
                          <span  v-if="auction.item.bids.length > 0 && bidders[auction.item.bids[0].user_id]" id="highest_bidder">
                            @{{ bidders[auction.item.bids[0].user_id].name }} ($ @{{  Math.round(auction.item.bids[0].amount) }})
                          </span>
                          <span v-else>No bids yet</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 control-item">
                    <div class="row">
                      <div class="control-wrapper">
                        <div class="control">
                          <div class="container-fluid bidding-controls" v-if="auction.queue && auction.queue.length">
                            <div class="row">
                              <div class="col-3">
                                <button @click="lowerBid" class="btn bid-button control-ui"><i class="fa fa-minus"></i></button>
                              </div>
                              <div class="col-3">
                                <input @focus="$event.target.select()" id="manualBid" type="number" v-model="bid_amount" class="control-ui">

                              </div>
                              <div class="col-3">
                                <button id="bidButton" @click="bid" class="btn bid-button control-ui">Bid</button>
                              </div>
                              <div class="col-3">
                                <button @click="raiseBid" class="btn bid-button control-ui"><i class="fa fa-plus"></i></button>
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
          </div>




        </div>



      </div>

      <b-alert class="minimum-bid-warning" variant="danger"
               dismissible
               :show="showMinimumBidWarning"
               @dismissed="showMinimumBidWarning=false">
      Your bid cannot be lower than the minimum bid!
      </b-alert>

    </div>




















    </div>



    <div class="widgets">

      <div class="widget">

      </div>

    </div>


  </div>


  <div class="auction" style="display: none;">   <!-- the entire auction room -->




    <nav class="top-bar"> <!-- top bar nav -->
      <div class="row">
      	<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="row">



            <div class="col-4 col-sm-2 col-md-2 col-lg-3 d-xl-block col-xl-2">
              <div v-if="auction.status">
                Status:
                <span v-if="auction.status.status.in_progress == true">@{{ auction.status.status.label }}</span>
                <span v-else class="blinking">@{{ auction.status.status.label }}</span>
              </div>

            </div>

            <div class="d-none d-sm-none d-md-none d-lg-none d-xl-block col-xl-2">
              <span v-if="auction.item">On the Block: </span>
            </div>

            <div class="d-none d-sm-block col-sm-5 col-md-5 col-lg-3 col-xl-2">
              <p v-if="auction.item.bids.length > 0 && bidders[auction.item.bids[0].user_id]" id="hightest_bidder">
                Highest Bidder:
                @{{ bidders[auction.item.bids[0].user_id].name }} ($ @{{  Math.round(auction.item.bids[0].amount) }})
              </p>
            </div>


            <div class=" d-none d-sm-none d-md-none  d-xl-block col-xl-2">
                <span v-if="bidders[user.id]">Logged In: @{{ bidders[user.id].name }} ($ @{{ bidders[user.id].spend }})</span>
            </div>

            <div class="owner-tools col-4 col-sm-3 col-md-4 col-lg-3 col-xl-2">
              <div class="row icons">
                <div v-if="showOwnerControls" class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                  <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a id="owner-tools" class="nav-link dropdown-toggle text-right pull-right" href="/auctions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fa fa-cog"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="owner-tools">
                          <a class="dropdown-item" href="#" @click="startNextItem">Start Next Item</a>
                          <a class="dropdown-item" href="#" @click="resetTimer(auction.bid_timer)">Restart Clock</a>
                          <a class="dropdown-item" href="#" @click="undoLastBid">Undo Last Bid</a>
                          <a class="dropdown-item" href="#" >End Auction</a>
                          <a class="dropdown-item" href="#" >Reload App</a>
                          <a class="dropdown-item" href="#" @click="showModal"><i class="fa fa-plus"></i> Add Item</a>
                        </div>
                    </li>
                  </ul>
                </div>
                <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                  <a href="#"><span><i class="fa fa-flag"></i></span></a>
                </div>
                <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                  <a href="#"><span><i class="fa fa-bell"></i></span></a>
                </div>
                <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                  <a @click="toggleStatus" href="#"><span><i class="fa fa-power-off"></i></span></a>
                </div>
              </div>
            </div>
          </div>
      	</div>
      </div> <!-- end of row -->
    </nav> <!-- end top bar nav -->



    <nav class="bidding navbar-dash navbar-expand-md navbar-light text-center resizable"> <!-- bidding controls bar     -->
      <div class="row">

        <div class="logo-container col-sm-12 col-md-12 d-md-block d-lg-none d-xl-block col-xl-2 ">
          <a class="navbar-brand" href="/home">
              <img class="logo-large" src="/images/logo.png" />
          </a>
      	</div>

        <div class="logo-container d-none d-sm-none d-md-none col-lg-1 d-lg-block d-xl-none">
          <a class="" href="/home">
              <img class="logo-small" src="/images/footer_logo.png" />
          </a>
      	</div>

        <div class="col-3 col-sm-3 col-md-2 col-lg-1 col-xl-1 ">

        </div>


        <div class="bidding-controls controls col-9 col-sm-9 col-md-5 col-lg-4 col-xl-3">
          <div v-if="auction.queue && auction.queue.length">
        		<button @click="lowerBid" class="btn bid-button"><i class="fa fa-minus"></i></button>
            <input @focus="$event.target.select()" id="manualBid" type="number" v-model="bid_amount">
              <b-alert class="minimum-bid-warning" variant="danger"
                       dismissible
                       :show="showMinimumBidWarning"
                       @dismissed="showMinimumBidWarning=false">
              Your bid cannot be lower than the minimum bid!
              </b-alert>
            <button id="bidButton" @click="bid" class="btn bid-button">Bid</button>
        		<button @click="raiseBid" class="btn bid-button"><i class="fa fa-plus"></i></button>

          </div>
        </div>


        <div class="bidding-controls d-md-block col-md-5 col-lg-3 col-xl-3">

            <h1 v-if="auction.item.name">
              @{{ auction.item.name }}
            </h1>
            <h1 v-else class="blinking">Waiting... </h1>

        </div>

        <div class="bidding-controls d-md-none d-lg-none d-xl-block col-xl-3">
          <h2 id="current_bid" v-if="auction.item.bids.length > 0 && bidders[auction.item.bids[0].user_id]">
            @{{ bidders[auction.item.bids[0].user_id].name }}
            ($@{{ Math.round(auction.item.bids[0].amount) }})
          </h2>
          <h2 v-else>No bids yet.</h2>
        </div>





      </div>
    </nav> <!-- end bidding controls bar -->



    <div class="row justify-content-center"> <!-- the main content area     -->
      <div class="col-md-12 auction-room"> <!-- full width container for the auction room    -->





            <div class="items"  >
                 <div class="widget-card">
                   <h3>Items</h3>
                   <input id="showCompletedItems" type="checkbox" v-model="showCompletedItems" name="" value="">
                   <label for="showCompletedItems">
                     <span>Show Completed Items</span>
                   </label>
                   <div v-if="auction.queue" class="widget-body scrollable col-md-12">
                     <table class="table">
                       <tr>
                         <th>Item</th><th v-if="showCompletedItems">Winning Bid</th><th v-if="showCompletedItems">Bidder</th>
                       </tr>
                       <tr v-for="(item,index) in auction.items">
                           <td v-if="isActive(item.id)">@{{ auction.items[index].name }}</td>
                           <td v-if="!isActive(item.id) && showCompletedItems" class="disabled">@{{ auction.items[index].name }}</td>
                           <td v-if="!isActive(item.id) && item.bids.length > 0 && showCompletedItems" >$ @{{ auction.items[index].bids[0].amount }} </td>
                           <td v-if="( isActive(item.id) || ( !(item.bids.length > 0) ) ) && showCompletedItems "> - </td>
                           <td v-if="!isActive(item.id) && item.bids.length > 0 && showCompletedItems" > @{{ bidders[auction.items[index].bids[0].user_id].name }} </td>
                           <td v-if="( isActive(item.id) || ( !(item.bids.length > 0) ) ) && showCompletedItems "> - </td>
                       </tr>
                     </table>
                     </ul>
                   </div>
                 </div>
                 <div class="col-md-2">
                   <b-modal ref="myModalRef" hide-footer title="Add Item">
                     <form id="addItem" @submit.prevent="addItem" role="form">
                       <div class="form-group">
                         <label for="name">
                           Item Name
                         </label>
                         <input v-model="name" type="text" class="form-control" id="itemName" />
                         <hr>
                         <label for="exampleFormControlFile1">Import from CSV</label>
                         <input type="file" class="form-control-file" id="itemsCsv">


                       </div>
                       <b-btn class="mt-3" block @click="addItem">Add</b-btn>
                     </form>
                   </b-modal>
                 </div>
            </div> <!-- end items widget -->



            <div >
               <div class="widget-card ">
                 <h3>Queue</h3>
                 <div class="widget-body scrollable col-md-12">

                 </div>
               </div>
            </div> <!-- end queue widget -->

            <div >
                <div class="widget-card chat-widget">
                   <h3>Chat</h3>
                   <div class="widget-body">
                      <div class="col-md-12">
                        <div class="row">
                           <div class="col-sm-12 col-md-12">
                             <div id="chat-widget-body" class="panel-body">
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
                             {{-- <div class="row">
                               <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-8">
                                 <div class="input-group">
                                   <label for="messageTo"><span>To (private)</span></label>
                                   <select id="messageTo" class="form-control input-sm" v-model="messageTo">
                                     <option v-for="bidder in messagers" >@{{ bidder.name }}</option>
                                   </select>
                                 </div>
                               </div>
                             </div> --}}
                           </div>
                         </div>
                      </div>
                   </div>
                </div>
            </div> <!-- end chat widget -->



            <div>


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




               <div class="bidders-overview container">
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
            </div> <!-- end bidders widget -->



        </div> <!-- end full width container -->
    </div> <!-- end main content area     -->


  </div> <!-- end auction room -->

@endsection
