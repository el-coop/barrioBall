<template>
    <div class="card">
        <div class="card-header">{{this.sender}}</div>
        <div class="card-body">
            <div v-for="message in messages" class="d-flex flex-column mb-1"
                 :class="messageClass(message.user_id)">
                <span class="message px-2 pt-2 pb-1" :class="[messageBackground(message.user_id)]">
                    <h6 v-if="message.action" v-html="message.action"></h6>
                    <p class="m-0">
                        {{message.text}}
                    </p>
                    <small class="text-muted d-block" :class="{'text-right' : ! isCurrent(message.user_id)}">
                        {{message.date}}
                        {{message.time}}
                    </small>
                </span>
            </div>
        </div>
        <div class="card-footer">
            <ajax-form event="sent" ref="form"
                       btnWrapperClass="d-none"
                       :action="'/user/conversations/' + conversation">
                <div class="input-group">
                    <input type="text" name="message" id="message" class="form-control" v-model="input">
                    <div class="input-group-append">
                        <button class="btn btn-info" type="submit" :disabled="sending || input == ''"
                                @click="sending = true">Button
                        </button>
                    </div>
                </div>
            </ajax-form>
        </div>
    </div>
</template>
<style scoped>
    .current-user {
        background-color: #dcf8c6;
    }

    .sender {
        background-color: #f3f4f5;
    }

    .message {
        border-radius: 1rem;
        max-width: 65%;
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
			},
		},
		data() {
			return {
				messages: [],
				sending: false,
				input: ''
			}
		},

		created() {
			this.$bus.$on('new-notification', (notification) => {
				if (notification.conversation == this.conversation) {
                    let reset = false;
					if(notification.action == null){
						reset = true;
                    }
					if (notification.message.user_id != this.currentUser) {
						this.$bus.$emit('decrement-notifications');
						reset = false;
					}
					this.updateConversation(notification.message, reset);
					this.markAsRead();
				}
			});
		},
		beforeDestroy() {
			this.$bus.$off('new-notification');
		},

		watch: {
			conversation() {
				this.load();
			},
		},

		methods: {
			isCurrent(userId) {
				return this.currentUser == userId;
			},

			updateConversation(data, resetInput = true) {
				this.messages.push({
					action: data.action,
					text: data.text,
					user_id: data.user_id,
					date: data.date,
					time: data.time
				});
				if (resetInput) {
					this.input = '';
					this.sending = false;
				}
				this.scrollToBottom();
			},

			markAsRead() {
				axios.post('/user/conversations/read/' + this.conversation).then(() => {
				});
			},

			load() {
				axios.get('/user/conversations/' + this.conversation).then((response) => {
					this.messages = response.data;
					this.scrollToBottom();
				});
			},

			scrollToBottom() {
				setTimeout(() => {
					let chat = this.$el.querySelector(".card-body");
					chat.scrollTop = chat.scrollHeight;
				}, 50);
			},

			messageClass(userId) {
				if (!this.isCurrent(userId)) {
					return ['align-items-start'];
				} else {
					return ['align-items-end'];
				}
			},

			messageBackground(userId) {
				if (!this.isCurrent(userId)) {
					return 'sender';
				} else {
					return 'current-user';
				}
			}
		},
	}
</script>