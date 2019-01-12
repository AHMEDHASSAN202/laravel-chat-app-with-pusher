<template>
<div class="inbox_msg">

  <div class="inbox_people">
    <div class="headind_srch">
      <div class="recent_heading">
        <h4>Online Users</h4>
      </div>
      <div class="srch_bar">
        <div class="stylish-input-group">
          <input type="text" class="search-bar"  placeholder="Search" >
          <span class="input-group-addon">
            <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
          </span>
        </div>
      </div>
    </div>
    <div class="inbox_chat">
      <div v-for="user in onlineUsers" v-on:click="getMessages(user)" class="chat_list" style="cursor: pointer">
        <div class="chat_people">
          <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
          <div class="chat_ib">
            <h5>{{ user.name }}</h5>
            <p>{{ user.email }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="mesgs">
    <div class="msg_history" id="msg_history">
      <!-- Messages -->
      <div  v-if="messages.length >= 1" v-for="message in messages">
        <div v-if="message.sender.id != auth.id" class="incoming_msg">
          <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
          <div class="received_msg">
            <div class="received_withd_msg">
              <p>{{ message.message }}</p>
              <span class="time_date"> {{ message.created_at }} </span>
            </div>
          </div>
        </div>
        <div v-if="message.sender.id == auth.id" class="outgoing_msg">
          <div class="sent_msg">
            <p>{{ message.message }}</p>
            <span class="time_date">{{ message.created_at }}</span>
          </div>
        </div>
      </div>
      <div v-else>
        <h3>write now first message :)</h3>
      </div>
      <!-- #END# messages -->
    </div>
    <div class="type_msg">
      <div class="input_msg_write">
        <input type="text" name="message" v-model="new_message" @keyup.enter="add_new_message" class="write_msg" placeholder="Type a message" />
        <button v-on:click="add_new_message" class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
      </div>
    </div>
  </div>

</div>
</template>

<script>
    export default {
        props: ['auth'],
        data: function(){
            return {
                onlineUsers: [],
                messages: [],
                new_message: '',
                receive_id: null
            };
        },
        mounted() {
            window.Echo.join('online')
                .here((users) => {
                    for (let i = 0; i < users.length; i++) {
                        if (users[i].id != this.auth.id) {
                            this.onlineUsers.push(users[i]);
                        }
                    }
                })
                .joining((user) => {
                    if (this.onlineUsers.indexOf(user) === -1) {
                        this.onlineUsers.push(user)
                    }
                })
                .leaving((user) => {
                    for (let i = 0; i < this.onlineUsers.length; i++) {
                        if (this.onlineUsers[i].id === user.id) {
                            this.onlineUsers.splice(i, 1);
                        }
                    }
                });

            //messages listen
            window.Echo.private('chat').listen('MessageSent', (event) => {
                console.log(event);
                if (this.auth.id == event.message.sender.id || this.auth.id == event.message.receiver.id) {
                    this.messages.push(event.message);
                    console.log(this.messages);
                }
            });

        },
        methods: {
            getMessages: function(user) {
                window.axios.post('/chat/messages', {sender_id: this.auth, receive_id: user.id}).then((data) => {
                    this.receive_id = user.id;
                    this.messages = data.data;
                })
            },
            add_new_message: function() {
                if (this.new_message !== '' && this.receive_id !== null) {
                    window.axios.post('/chat/message', {receive_id: this.receive_id, message: this.new_message}).then(e => {
                        this.new_message = '';
                    });
                }
            }
        }
    }
</script>

