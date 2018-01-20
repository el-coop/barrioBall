<style scoped>
    .list-group {
        overflow-y: auto;
    }

    .btn-col {
        background-color: white;
    }
</style>

<script>
	export default {
		props: {
			initConversations: {
				type: Array
			},
			currentUser: {
				type: Number,
				required: true
			}
		},

		data() {
			return {
				currentConversation: 0,
				sender: '',
                conversations: this.initConversations
			}
		},


		methods: {
			changeConversation(conversation, toggleConversation = true) {
				conversation.pivot.read = true;
				this.currentConversation = conversation.id;
				this.sender = conversation.users.find((user) => {
					return user.id != this.currentUser;
				}).username;
				if(toggleConversation){
					this.$refs.mobileView.toggle(1);
                }
			}
		},

		created() {
			this.$bus.$on('new-notification', (notification) => {
				console.log(notification);
				let conversationIndex = this.conversations.findIndex((conversation)=>{
                    return conversation.id == notification.conversation;
                });
				if(conversationIndex > -1){
					let tempConversation =  this.conversations.splice(conversationIndex,1)[0];
					if(notification.message.user_id != this.currentUser && tempConversation.id !=  this.currentConversation){
						tempConversation.pivot.read = false;
					}
					this.conversations.unshift(tempConversation);
                }
			});
		},
		beforeDestroy() {
			this.$bus.$off('new-notification');
		},


		mounted() {
			this.changeConversation(this.conversations[0], false);
		},
	}
</script>