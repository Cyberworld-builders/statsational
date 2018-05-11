<template >

  <div class="row items">
    <div class="col-sm-12 col-md-12 ">
      <div class="card">
          <div class="card-header">Items</div>
          <div class="card-body">
              <div class="col-md-12">
              </div>
              <ul>
                <li v-for="item in this.items">
                  {{ item.name }} atest
                </li>
              </ul>
              <b-btn v-b-modal.itemForm class="btn btn-primary form-control"><i class="fa fa-plus"></i> Add Item</b-btn>
          </div>
      </div>
    </div>
    <div class="col-xs-3 col-md-2">
      <b-modal id="itemForm" ref="itemFormRef" hide-footer title="Add Item">
        <form id="addItem" @submit.prevent="addItem" role="form">
          <div class="form-group">
            <label for="name">
              Item Name
            </label>
            <input v-model="name" type="text" class="form-control" id="itemName" />
          </div>
          <b-btn class="mt-3" block @click="addItem">Add</b-btn>
        </form>
      </b-modal>
    </div>
  </div>

</template>

<script>
    import axios from 'axios'
    export default {
        name: 'auction-items',
        props: ['auction_id','auction','items'],

        data: function(){
          return {
            name: ""
          }
        },

        methods:{
          showModal: function(){
            this.$refs.itemFormRef.show();
          },
          hideModal: function(){
            this.$refs.itemFormRef.hide();
          },

          addItem: function(){
            // axios.post('/auctions/addItem',{
            //   name: this.name,
            //   auction_id: this.auction_id
            // }).then(function(response){
            //   // var items = response.data;
            //   // console.log( response.data );
            //
            //   // this.auction.items = response.data;
            //   // this.hideModal();
            // }).catch(e => {
            //   console.log(e);
            // });
            // this.items.push("balls");
            console.log( this.items );
          }
        },



        mounted: function(){
          console.log(this.items);
        }


    }
</script>
