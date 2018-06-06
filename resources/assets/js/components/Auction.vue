<template>
  <div class="auction">
    <slot></slot>
  </div>

</template>

<script>
    import Countdown from 'vuejs-countdown'

    export default {
      data(){
        return {
          bid_amount: 0
        }
      },
      props: ['auctionAction'],
        mounted() {
            console.log('Auction mounted.')
        }
    }
</script>
