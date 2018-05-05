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
        <label>
          <input v-model="private" type="checkbox" /> Make Private
        </label>
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
              private: this.private
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
            private: false
          }
        },


    }
</script>
