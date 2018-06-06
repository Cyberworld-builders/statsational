<template>
    <div class="bidders-overview">
      <div class="row">
      	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-8">
      		<h3>Bidders Overview</h3>
      	</div>
      </div>

      <div v-for="bidder in bidders" >
        <div class="bidder-card">
          	{{ bidder.name }}
        </div>
      </div>


    </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'bidders-overview',
    props: ['auction','bids'],
    data: function(){
      return {
        bidders: []
      }
    },
    methods: {

    },
    mounted: function(){
      console.log(this.auction.users);
      this.bidders.push(this.auction.user);
      for(var i=0;i<this.auction.users.length;i++){
        this.bidders.push(this.auction.users[i]);
      }
    }
  }
</script>
