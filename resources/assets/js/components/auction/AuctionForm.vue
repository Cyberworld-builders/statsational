<template >

  <form id="createAuction" @submit.prevent="createAuction" role="form">

    <div class="form-group">
      <label for="name">
        Pool Name
      </label>
      <input v-model="name" type="text" class="form-control" id="poolName" />
    </div>

    <div class="form-group">
      <label for="poolRules">
        Rules
      </label>
      <editor id="poolRules" v-model="rules" api-key="xecwpxd2so72hu32w8wv7l3aoa2q3c66qkr2f5hufeuisljj" :init="{plugins: 'wordcount'}"></editor>
    </div>



    <div class="form-group">
      <label for="start_time">
        Start Time
      </label>
      <datetime
          v-model="start_time"
          type="datetime"
            input-class="form-control"
            use12-hour
      ></datetime>
    </div>

    <div class="form-group">

          <div class="checkbox">
            <label for="private">Make Private:</label>
            <input id="private" v-model="private" type="checkbox" />

            <label v-if="private" for="password">Auction Password:</label>
            <input v-if="private" v-model="password" type="text" name="password" value="" />
          </div>

          <div class="checkbox">
            <label for="manual_next">Require admin to manually start the next item:</label>
            <input id="manual_next" v-model="manual_next" type="checkbox" />
          </div>

          <div class="number">
            <label for="bid_timer">Bid Timer (seconds): </label>
            <input id="bid_timer" v-model="bid_timer" type="number" />
          </div>

          <div class="number">
            <label for="snipe_time">Snipe Time (seconds): </label>
            <input id="snipe_time" v-model="snipe_time" type="number" />
          </div>



          <div class="number">
            <label for="bid_increment">Bidding Increment: </label>
            $ <input id="bid_increment" v-model="bid_increment" type="number" />
          </div>

      </div>




    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary input-block-level form-control">
        Create
      </button>
    </div>



  </form>

</template>

<script>
    import Datetime from 'vue-datetime'
    import 'vue-datetime/dist/vue-datetime.css'
    import axios from 'axios'
    // es modules
    import Editor from '@tinymce/tinymce-vue';
    // commonjs require
    // var Editor = require('@tinymce/tinymce-vue');


    // tinymce api key
    // xecwpxd2so72hu32w8wv7l3aoa2q3c66qkr2f5hufeuisljj

    export default {

        name: 'auction-form',

        components: {
          'editor': Editor
        },

        methods:{
          createAuction: function(){
            axios.post('/auctions/store',{
              name: this.name,
              rules: this.rules,
              start_time: this.start_time,
              private: this.private,
              manual_next: this.manual_next,
              bid_timer: this.bid_timer,
              snipe_time: this.snipe_time,
              bid_increment: this.bid_increment,
              password: this.password
            }).then(function(response){
              console.log(response.data);
              window.location.href = '/auction/' + response.data;
            }).catch(e => {
              console.log(e);
            });
          }
        },

        data: function(){
          return {
            name: "",
            rules: "",
            start_time: "",
            private: false,
            password: "",
            manual_next: false,
            bid_timer: 30,
            snipe_time: 15,
            bid_increment: 1
          }
        },


    }
</script>
