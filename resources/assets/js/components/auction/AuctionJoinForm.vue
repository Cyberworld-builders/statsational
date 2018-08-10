<template>
  <form id="joinAuction" @submit.prevent="joinAuction" role="form">
    <div class="form-group ">

      <h2>Do you want to join {{ this.name }}?</h2>
      <p v-html="this.rules"></p>
      <p v-if="this.private">
        <label for="password">Enter password:</label>
        <input v-model="password" type="text" name="password" value="" />
        <b-alert class="minimum-bid-warning" variant="danger"
                 dismissible
                 :show="showWrongPasswordWarning"
                 @dismissed="showWrongPasswordWarning=false">
        Incorrect password!
        </b-alert>
      </p>

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
        rules: "",
        private: true,
        password: "",
        showWrongPasswordWarning: false
      }

    },
    methods: {
      joinAuction: function(){
        var auction = JSON.parse(this.auction);
        console.log(auction.settings.password + ":::" + this.password);

        if(this.private === false || this.password == auction.settings.password){
          axios.post('/auctions/join',{
            auction_id: this.auction_id,
            password: this.password
          }).then(function(response){
            window.location.href = '/auction/' + response.data;
          }).catch(e => {
            console.log(e);
          });
        } else {
          this.showWrongPasswordWarning = true;
        }

      }
    },
    mounted() {
      var auction = JSON.parse(this.auction);
      this.name = auction.name;
      this.rules = auction.rules;
      this.private = auction.private;
      console.log(auction);
    }
  }
</script>
