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
			conversations: {
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
				sender: ''
			}
		},


		methods: {
			changeConversation(conversation, toggleConversation = true) {
				this.currentConversation = conversation.id;
				this.sender = conversation.users.find((user) => {
					return user.id != this.currentUser;
				}).username;
				if(toggleConversation){
					this.$refs.mobileView.toggle(1);
                }
			}
		},


		mounted() {
			this.changeConversation(this.conversations[0], false);
		},
	}
</script>