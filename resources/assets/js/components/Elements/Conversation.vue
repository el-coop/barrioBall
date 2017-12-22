<template>
    <div class="card">
        <div class="card-header">{{this.sender}}</div>
        <div class="card-body">
            <div v-for="message in messages" :class="{'text-right': isCurrent(message.user_id)}"
                 class="message-wrapper">
            <span class="message"
                  :class="[isCurrent(message.user_id) ? 'current-user' : 'sender']">{{message.text}}</span>
            </div>
        </div>
        <div class="card-footer">
            <ajax-form event="sent" ref="form"
                       :action="'/user/conversations/' + conversation"
                       @sent="updateConversation">
                <div class="form-group">
                    <input type="text" name="message" id="message" class="form-control" required>
                </div>
                <span class="float-right" slot="submit">{{this.btnTxt}}</span>
            </ajax-form>
        </div>
    </div>
</template>
<style scoped>
    .current-user {
        text-align: left;
        background-color: #dcf8c6;
    }

    .sender {
        background-color: #cccdce;
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
                    setTimeout(()=>{
                        let chat = this.$el.querySelector(".card-body");
                        chat.scrollTop = chat.scrollHeight;
                    },50);
                });

            },
        },

        methods: {
            isCurrent(userId) {
                return this.currentUser == userId;
            },

            updateConversation(response, data) {
                this.messages.push({'text': response.message, 'user_id': this.currentUser});
            },
        },
    }
</script>