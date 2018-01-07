<template>
    <div class="card">
        <div class="card-header">{{this.sender}}</div>
        <div class="card-body">
            <div v-for="message in messages" :class="{'text-right': isCurrent(message.user_id)}"
                 class="message-wrapper">
            <span class="message"
                  :class="[isCurrent(message.user_id) ? 'current-user' : 'sender']">
                <h5 v-if="message.action_type">
                    {{message.action_type}} <a :href="'/matches/' + message.action_match_id">{{message.action_match}}</a>
                </h5>
                <p>{{message.text}}</p></span>
                <span class="time"
                      :class="[isCurrent(message.user_id) ? 'current-user-time' : 'sender-time']">{{message.date}} {{message.time}}</span>
            </div>
        </div>
        <div class="card-footer">
            <ajax-form event="sent" ref="form"
                       :action="'/user/conversations/' + conversation"
                       @sent="updateConversation">
                <div class="form-group">
                    <input type="text" name="message" id="message" class="form-control" required>
                </div>
                <span class="float-lg-right" slot="submit">{{this.btnTxt}}</span>
            </ajax-form>
        </div>
    </div>
</template>
<style scoped>
    .current-user {
        background-color: #dcf8c6;
        float: right;
        clear: right;
    }

    .sender {
        background-color: #cccdce;
        float: left;
        clear: left;
    }

    .current-user-time {
        float: right;
        clear: right;
    }

    .sender-time {
        float: left;
        clear: left;
    }

    .time {
        margin: 0px 12px;
        font-size: 10px;
    }

    .message {
        border-radius: 26px;
        max-width: 65%;
        margin: 5px;
        padding: 10px;
    }

    .message-wrapper {
        margin: 18px 0px;
    }

    .card-body {
        height: 60vh;
        overflow-y: auto;
    }
</style>
<script>
    export default {
        props: {
            conversation: {
                type: Number,
                required: true
            },

            currentUser: {
                type: Number,
                required: true
            },
            sender: {
                type: String,
                required: true
            },
            btnTxt: {
                type: String,
                required: true
            }
        },
        data() {
            return {
                messages: []
            }
        },

        watch: {
            conversation() {
                axios.get('/user/conversations/' + this.conversation).then((response) => {
                    this.messages = response.data;
                    setTimeout(() => {
                        let chat = this.$el.querySelector(".card-body");
                        chat.scrollTop = chat.scrollHeight;
                    }, 50);
                });

            },
        },

        methods: {
            isCurrent(userId) {
                return this.currentUser == userId;
            },

            updateConversation(response, data) {
                this.messages.push({
                    'text': response.message,
                    'user_id': this.currentUser,
                    'date': moment().format('M/D/Y'),
                    'time': moment().format('HH:mm')
                });
                $('#message').val('');
            },
        },
    }
</script>