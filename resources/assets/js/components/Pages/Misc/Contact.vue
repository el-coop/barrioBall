<script>
	export default {
		data(){
			return {
				sending: false,
                sent: false,
                error: false
            }
        },

        methods: {
			showError(value){
				this.sending = false;
				this.error = value.message[0];
            }
        }
    }
</script>
