<template>
  <form id="auctionBidding" @submit.prevent="auctionBidding" role="form">
    <div class="row bidding">
      <div class="col-sm-12 col-md-6 ">
        <div class="row info-block">
            <div class="hidden-xs hidden-sm col-md-3"></div>
            <div class="col-md-8 col-lg-8">
              <h3>Russell Henley (#50)</h3>
              <ul>
                <li>Bid: $ {{ bid_amount }}</li>
                <li>Time Remaining: 00:01</li>
                <li>High Bid: $ {{ current_bid }}</li>
              </ul>
            </div>
            <div class="hidden-xs hidden-sm col-md-1 "></div>
            <div class="col-md-12 text-center">
              <hr />
              <p>Current Purchases: ($0) - Total: ($0)</p>
              <button class="btn btn-primary form-control input-block-level">$1 (min bid)</button>
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
                        <button class="btn btn-primary small form-control"><strong>Bid</strong></button>
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
                </div>
            </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'auction-bidding',
    props: ['auction_id','current_bid'],
    data: function(){
      return {
        bid_amount: 0
      }
    },
    methods: {
      auctionBidding: function(){
        axios.post('/auctions/bid',{
          auction_id: this.auction_id,
          bid_amount: this.bid_amount
        }).then(function(response){
          console.log(response.data);
          window.location.href = '/auction/' + response.data;
        }).catch(e => {
          console.log(e);
        });
          console.log('bidding, yo!');
      },
      lowerBid: function(){
        this.bid_amount--;
      },
      raiseBid: function(){
        this.bid_amount++;
      }
    },
    mounted: function(){

    }
  }
</script>
