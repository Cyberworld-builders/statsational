<template scope="{action}">
  <div class="col-xs-3 col-md-2">
    <button @click="auctionAction" class="btn btn-primary form-control">
      <slot></slot>
    </button>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
      name: 'auction-control',
      props: ['modal'],
      methods:{
        startNextItem: function(e){
          console.log("show next item");
        },
        auctionAction: function(){
          // console.log();
          // if(this.action == "startNextItem"){
          //   this.startNextItem();
          // }

        }
      },
      data(){
        return {
          action: "startNextItem"
        }
      }
  }
</script>
