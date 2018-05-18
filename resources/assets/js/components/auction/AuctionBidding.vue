<template>

    <div class="row bidding">
      <div class="col-sm-12 col-md-6 ">
        <div class="row info-block">
            <div class="hidden-xs hidden-sm col-md-3"></div>
            <div class="col-md-8 col-lg-8">
              <h3>{{ auction.queue[0].name }}</h3>
              <ul>
                <li>Bid: $ {{ bid_amount }}</li>
                <li>Time Remaining: 00:01</li>
                <li>High Bid: $ {{ Number(bids[0].amount) }}</li>
              </ul>
            </div>
            <div class="hidden-xs hidden-sm col-md-1 "></div>
            <div class="col-md-12 text-center">
              <hr />
              <p>Current Purchases: ($0) - Total: ($0)</p>
              <input type="hidden" v-model="minimum_bid">
              <button @click="minimumBid" class="btn btn-primary form-control input-block-level">${{ minimum_bid }} (min bid)</button>
              <br /><br />
              <div class="row">
                <div class="hidden-xs hidden-sm col-md-3"></div>
                <div class="col-sm-12 col-md-6">
                    <div class="row text-center">
                      <div class="col-md-3 bid-button">
                        <button @click="lowerBid" class="btn btn-secondary small form-control"><i class="fa fa-minus"></i></button>
                      </div>
                      <div class="col-md-3 bid-button">
                        <input class="form-control" type="number" v-model="bid_amount">
                      </div>
                      <div class="col-md-3 bid-button">
                        <button @click="raiseBid" class="btn btn-secondary small form-control"><i class="fa fa-plus"></i></button>
                      </div>
                      <div class="col-md-3 bid-button">
                        <button @click="auctionBidding" class="btn btn-primary small form-control"><strong>Bid</strong></button>
                      </div>
                    </div>
                  </div>
                <div class="hidden-xs hidden-sm col-md-3"></div>
              </div>
            </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 ">
        <div class="card">
            <div class="card-header">Bids</div>
            <div class="card-body">
                <div class="col-md-12">
                  <ul v-for="(bid, index) in bids">
                    <li>${{ Number(bid.amount) }}</li>
                  </ul>
                </div>
            </div>
        </div>
      </div>
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
        minimum_bid: 1
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
        if(new_bid > this.minimum_bid){
          this.bid_amount = new_bid;
        }
      },
      raiseBid: function(){
        var new_bid = Number(this.bid_amount) + 1;
        if(new_bid > this.minimum_bid){
          this.bid_amount = new_bid;
        }
      },
      minimumBid: function(){
        this.bid_amount = this.minimum_bid;
        this.auctionBidding();
      }
    },
    mounted: function(){
      console.log(this.bids);
      this.minimum_bid = Number(this.bids[0].amount) + 1;
      this.bid_amount = this.minimum_bid;
    }
  }
</script>
