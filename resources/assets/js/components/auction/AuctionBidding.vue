<template>
    <div>
      <nav class="bidding navbar-dash navbar-expand-md navbar-light">
          <div class="row">
            <div class="navbar-home col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-2">
              <a class="navbar-brand" href="/home">
                  <img class="logo-small" src="/images/logo.png" />
              </a>
            </div>
            <div class="bidding-controls col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                  <p>On the Block</p>
                  <p>{{ auction.queue[0].name }}</p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                  <p>Current Bid: $ {{ bid_amount }}</p>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                  <p>{{ auction.queue[0].name }}</p>
                </div>
              </div>
            </div>
            <div class="bidding-controls controls col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-7">
              <small>Quick Bid</small><button @click="minimumBid" class="btn bid-button">Bid ${{ minimum_bid }}</button>
              <small>Mannual Bid</small>
              <button @click="lowerBid" class="btn bid-button"><i class="fa fa-minus"></i></button>
              <input class="" type="number" v-model="bid_amount">
              <button @click="raiseBid" class="btn bid-button"><i class="fa fa-plus"></i></button>
              <button @click="auctionBidding" class="btn bid-button"><strong><i class="fa fa-angle-left"></i> &nbspBid</strong></button>
            </div>
          </div>
      </nav>
    </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'auction-bidding',
    props: ['auction','bids'],
    data: function(){
      return {
        bid_amount: 0,
        item_id: 0,
        minimum_bid: 1,
        high_bid: 0
      }
    },
    methods: {
      auctionBidding: function(){
        axios.post('/auctions/bid',{
          auction_id: this.auction.id,
          bid_amount: this.bid_amount,
          item_id: this.auction.queue[0].id
        }).then(function(response){
          console.log(response.data);
          location.reload();
        }).catch(e => {
          console.log(e);
        });
          console.log('bidding, yo!');
      },
      lowerBid: function(){
        var new_bid = Number(this.bid_amount) - 1;
        if(new_bid >= this.minimum_bid){
          this.bid_amount = new_bid;
        }
      },
      raiseBid: function(){
        var new_bid = Number(this.bid_amount) + 1;
        if(new_bid >= this.minimum_bid){
          this.bid_amount = new_bid;
        }
      },
      minimumBid: function(){
        this.bid_amount = this.minimum_bid;
        this.auctionBidding();
      }
    },
    mounted: function(){
      if(this.bids.length > 0){
        this.high_bid = Number(this.bids[0].amount);
      }
      // console.log(this.bids);
      this.minimum_bid = this.high_bid + 1;
      this.bid_amount = this.minimum_bid;

    }
  }
</script>
