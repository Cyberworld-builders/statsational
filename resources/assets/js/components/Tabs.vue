<template>
  <div class="row tabs">
    <div class="col-md-12">
      <div class="row">
        <slot></slot>
      </div>

    </div>
  </div>
</template>

<script>
    export default {
      name: 'Tabs',
      props: ['auctionAction'],
      data(){
        return{

        }
      }
    }
</script>
