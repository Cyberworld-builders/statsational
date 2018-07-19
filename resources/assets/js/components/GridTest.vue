<template>
  <div class="auction grid">
    <grid-layout
            :layout="layout"
            :col-num="12"
            :row-height="30"
            :is-draggable="true"
            :is-resizable="true"
            :is-mirrored="false"
            :vertical-compact="true"
            :margin="[10, 10]"
            :use-css-transforms="true"
    >


    <grid-item
               :x="layout[0].x"
               :y="layout[0].y"
               :w="layout[0].w"
               :h="layout[0].h"
               :i="layout[0].i">


    </grid-item>

    <grid-item
               :x="layout[1].x"
               :y="layout[1].y"
               :w="layout[1].w"
               :h="layout[1].h"
               :i="layout[1].i">
        test

    </grid-item>



    </grid-layout>
    <pre>{{ layout }}</pre>
  </div>
</template>

<script>

  var testLayout = [
      {name: "Items","x":0,"y":0,"w":9,"h":10,"i":"0"},
      {name: "Bidders","x":9,"y":0,"w":3,"h":22,"i":"2"},
      {name: "Chat","x":2,"y":10,"w":7,"h":12,"i":"3"},
      {name: "Queue","x":0,"y":10,"w":2,"h":12,"i":"4"},

  ];

  import VueGridLayout from 'vue-grid-layout'

  var GridLayout = VueGridLayout.GridLayout;
  var GridItem = VueGridLayout.GridItem;

  export default {
    name: 'grid-test',
    components: {
      GridLayout,
      GridItem
    },
    data(){
      return{
        layout: testLayout,
        isResizable: true
      }
    }
  }

</script>
