<template>
    <div class="input-group">
        <div class="col-sm-12 col-md-10 mt-1">
          <input id="btn-input" class="form-control" placeholder="Type a message..."  v-model="newMessage" @keyup.enter="sendMessage" />
        </div>
        <div class="col-sm-3 col-md-2 pull-right text-right mt-1">
          <button class="btn btn-primary " id="btn-chat" @click="sendMessage" >Send</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendMessage() {
                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage
                });

                this.newMessage = ''
            }
        }
    }
</script>
