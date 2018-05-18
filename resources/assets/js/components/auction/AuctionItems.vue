<template >

  <div class="row items">
    <div class="col-sm-12 col-md-12 ">
      <div class="card">
          <div class="card-header">Item Queue</div>
          <div class="card-body">
              <div class="col-md-12">
                <ul>
                  <li v-for="(item,index) in auction.queue">
                    <span v-if="index != 0">{{ auction.queue[index].name }}</span>
                  </li>
                </ul>
              </div>
              <b-button v-if="auction.user.id == user" @click="showModal" class="btn btn-primary form-control"><i class="fa fa-plus"></i> Add Item</b-button>
          </div>
      </div>
    </div>
    <div class="col-xs-3 col-md-2">
      <b-modal ref="myModalRef" hide-footer title="Add Item">
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
        props: ['auction','user'],

        data(){
          return {
            name: "",
            modalShow: false
          }
        },

        methods:{

          showModal: function(){
            this.$refs.myModalRef.show();
          },
          hideModal: function(){
            this.$refs.myModalRef.hide();
          },

          addItem: function(){
            var items = this.auction.items;
            axios.post('/auctions/addItem',{
              name: this.name,
              auction_id: this.auction.id
            }).then(function(response){
              items = response.data;
              location.reload();

            }).catch(e => {
              console.log(e);
            });
            this.auction.items = items;
            this.hideModal();
          }
        },

        mounted: function(){

        }


    }
</script>
