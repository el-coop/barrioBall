<script>
	export default {
        props: {
			initMessagesCount: {
				required: false,
                default: 0,
                type: Number
            }
        },

        data(){
        	return {
        		messagesCount: this.initMessagesCount
            }
        },

		created() {
			this.$bus.$on('new-notification', ()=>{
				this.messagesCount++;
			});

			this.$bus.$on('decrement-notifications', ()=>{
				this.messagesCount--;
			});
		},
		beforeDestroy() {
			this.$bus.$off('new-notification');
			this.$bus.$off('decrement-notifications');
		},
	}
</script>