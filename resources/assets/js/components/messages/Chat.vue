<template>
  <div class="row">
      <div class="col-sm-12 col-md-7">
        <div class="panel-body">
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
            <div id="scrollToNewMessage"></div>
        </div>
        <br />
        <div class="row">
          <div v-on:messagesent="addMessage" class="input-group">
              <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">
              <span class="input-group-btn">
                  <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                      Send
                  </button>
              </span>
          </div>

        </div>
      </div>
      <div class="col-sm-12 col-md-5">
        <h4>Online Users:</h4>

        <ul>
            {{ auction.user.name }} (owner)
            <div v-for="user in auction.users">
              <li>{{ user.name }}</li>
            </div>`
        </ul>
      </div>
    </div>
</template>

<script>
  export default {
    props: ['user','auction'],
    data(){
      return{
        newMessage: '',
        messages: []
      }
    },

    created() {
        this.fetchMessages();
        Echo.private('chat')
          .listen('MessageSent', (e) => {
            this.messages.unshift({
              message: e.message.message,
              user: e.user,
              created_at: e.message.created_at
            });
          });
    },
    methods: {
      fetchMessages() {
          axios.get('messages/' + this.auction.id).then(response => {
              this.messages = response.data;
          });
      },
      addMessage(message) {
          axios.post('/messages', message).then(response => {
            console.log(response.data);
            this.messages.push(response.data);
          });
      },
      sendMessage() {
          this.$emit('messagesent', {
              user: this.user,
              message: this.newMessage,
              auction: this.auction.id
          });
          axios.post('/messages', {
              user: this.user,
              message: this.newMessage,
              auction: this.auction.id
          }).then(response => {
            console.log(response.data);
            this.messages.unshift(response.data);
            // $('#scrollToNewMessage').scrollTop($('#scrollToNewMessage')[0].scrollHeight - $('#scrollToNewMessage')[0].clientHeight);
          });
          this.newMessage = ''
      }
    }
  };
</script>
`
