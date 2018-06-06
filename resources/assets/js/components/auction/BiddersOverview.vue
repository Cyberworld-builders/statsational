<template>
    <div class="bidders-overview">
      <div class="row">
      	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-8">
      		<h3>Bidders Overview</h3>
      	</div>
      </div>

      <div v-for="bidder in auction.users" >
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

      }
    },
    methods: {

    },
    mounted: function(){
      console.log(this.auction.users);

    }
  }
</script>
