<template>
  <div>

  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: 'auction-summary',
    props: ['auction_id'],
    auction: {},
    data() {
      return {

        auction: {
          name: "test"
        },

        summary: {
          winners: []
        }

      }

    },
    methods: {

    },
    mounted() {
      // var auction_id = getElementById('auction_id').value;
      // console.log("word");
    }
  }
</script>
