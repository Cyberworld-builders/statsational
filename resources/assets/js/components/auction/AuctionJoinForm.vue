<template>
  <form id="joinAuction" @submit.prevent="joinAuction" role="form">
    <div class="form-group">
      <h2>Do you want to join {{ this.name }}?</h2>
      <p v-html="this.rules"></p>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary input-block-level form-control">
        Join
      </button>
    </div>
  </form>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'join-form',
    props: ['auction_id','auction'],
    data() {
      return {
        name: "",
        rules: ""
      }

    },
    methods: {
      joinAuction: function(){
        axios.post('/auctions/join',{
          auction_id: this.auction_id
        }).then(function(response){
          console.log(response.data);
          window.location.href = '/auction/' + response.data;
        }).catch(e => {
          console.log(e);
        });
      }
    },
    mounted() {
      var auction = JSON.parse(this.auction);
      this.name = auction.name;
      this.rules = auction.rules;
      console.log(auction.name);
    }
  }
</script>
