<template>
    <ul class="chat">
        <li class="left clearfix" v-for="message in messages">
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font">
                        {{ message.user.name }}
                    </strong>
                    <span>{{ message.created_at }}</span>
                </div>
                <p>
                    {{ message.message }}
                </p>
            </div>
        </li>
    </ul>
</template>

<script>
  export default {
    props: ['auction','messages'],
    data(){
      return{
        // messages: []
      }
    },
    created() {
        this.fetchMessages(this.auction.id);
    },
    methods: {
        fetchMessages() {
          var messages = this.messages;
            axios.get('messages/' + this.auction.id).then(response => {
                messages = response.data;
                this.messages = messages;
                console.log(messages);
            });
        },
    //     addMessage(message) {
    //         this.messages.push(message);
    //
    //         axios.post('/messages', message).then(response => {
    //           console.log(response.data);
    //         });
    //     }
    }
  };
</script>
