@extends('layouts.summary')
@section('content')

  <input type="hidden" id="auction_id" value="{{ $auction_id }}">
  <input type="hidden" id="user_id" value="{{ $user_id }}">


  <div class="summary container-fluid">   <!-- the entire summary page -->

  	<div class="row">
      <div class="content col-12 offset-lg-1 col-lg-10 offset-xl-2 col-xl-8">

        <div v-if="auction.name" class="pool">

          <div class="overview">
            <h1>@{{ auction.name }}</h1>

          </div>

            <div class="items">
              <h2>Winners</h2>

              <table class="table">
                <thead>
                  <tr>
                    <th><a href="#" @click="sortByItem">Item</a></th>
                    <th><a href="#" @click="sortByCost">Cost</a></th>
                    <th><a href="#" @click="sortByWinner">Winner</a></th>

                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in query">
                    <td>@{{ item.name }}</td><td>$@{{ item.amount }}</td><td>@{{ item.winner }}</td>
                  </tr>
                </tbody>
              </table>



            </div>

            <div class="bid-log">
              <h2>Bid Log</h2>

              <p class="add-bid">
                <a v-if="auction.user.id == user.id" class="" href="#" data-toggle="modal" data-target="#add-bid"><i class="fa fa-plus"></i> Add Bid</a>

                <div id="add-bid" class="modal" tabindex="-1" role="dialog" aria-labelledby="add-bid-label"> <!-- add item modal -->
                  <div class="modal-dialog modal-lg widget" role="document">
                    <div class="modal-content widget-container">
                      <div class="modal-header widget-header">
                        <h3 class="modal-title" id="add-bid-label">Add Bid</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="widget-body p-2">
                        <form id="addBid" @submit.prevent="addBid" role="form">
                          <div class="form-group">
                            <div class="container">
                              <div class="row">
                                <div class="col-12">
                                  <table class="table">
                                    <thead><tr><th>Item</th><th>Bid Amount</th><th>Participant</th></tr></thead>
                                    <tbody>
                                      <tr>
                                        <td>
                                          <div class="dropdown show">
                                            <a class=" dropdown-toggle" href="#" role="button" id="newBidItemDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <span v-if="new_bid.item_id != ''">@{{ auction.item_refs[new_bid.item_id].name }}</span>
                                              <span v-else>Select Item</span>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="newBidItemDropdown">
                                              <div v-for="item in auction.items">
                                                <a @click="new_bid.item_id = item.id" class="dropdown-item" href="#">@{{ item.name }}</a>
                                              </div>
                                            </div>
                                          </div>
                                        </td>
                                        <td>
                                          $<input class="bid-amount" type="number" v-model="new_bid.bid_amount" />
                                        </td>
                                        <td>
                                          <div class="dropdown show">
                                            <a class=" dropdown-toggle" href="#" role="button" id="newBidParticipantDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <span v-if="new_bid.user_id != ''">@{{ auction.participants[new_bid.user_id].name }}</span>
                                              <span v-else>Select Participant</span>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="newBidParticipantDropdown">
                                              <a @click="new_bid.user_id = auction.user.id" class="dropdown-item" href="#">@{{ auction.user.name }}</a>
                                              <div v-for="participant in auction.users">
                                                <a @click="new_bid.user_id = participant.id" class="dropdown-item" href="#">@{{ participant.name }}</a>
                                              </div>
                                            </div>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                            <b-btn class="mt-3 add-bid-button" block @click="addBid">Add Bid</b-btn>
                          </div>
                        </form>
                      </div> <!-- end widget-body -->
                    </div> <!-- end modal-content -->
                  </div> <!-- end modal-dialog -->
                </div> <!-- end add bid modal -->
              </p>

              <table class="table">
                <thead>
                  <tr>
                    <th>Time</th><th>Item</th><th>Bid Amount</th><th>Participant</th><th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="bid in auction.bid_log">
                    <td>@{{ bid.time }}</td>
                    <td>@{{ bid.item }}</td>
                    <td>
                      $@{{ bid.amount }}
                    </td>
                    <td>@{{ bid.participant }}</td>
                    <td v-if="auction.user.id == user.id">
                      <a class="m-1" href="#" @click="trashBid(bid.id)"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

        </div>



        <div class="items">

        </div>


      </div>
  	</div>

  </div>


@endsection
