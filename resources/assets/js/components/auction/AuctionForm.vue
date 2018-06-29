<template >

  <form id="createAuction" @submit.prevent="createAuction" role="form">

    <div class="form-group">
      <label for="name">
        Pool Name
      </label>
      <input v-model="name" type="text" class="form-control" id="poolName" />
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
      </div>
      <div class="number">
        <label for="bid_increment">Bidding Increment: </label>
        $ <input id="bid_increment" v-model="bid_increment" type="number" />
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
    export default {

        name: 'auction-form',

        methods:{
          createAuction: function(){
            axios.post('/auctions/store',{
              name: this.name,
              start_time: this.start_time,
              private: this.private,
              bid_increment: this.bid_increment
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
            start_time: "",
            private: false,
            bid_increment: 1
          }
        },


    }
</script>
