<script>
	export default {
		props: {
			sweetAlert: {
                default: ''
            },
			sweetAlertClass: {
                default: 'success'
            },
            sweetAlertTitle: {
                default: 'Success'
            }
        },

        mounted(){
			if(this.sweetAlert != ''){
				this.$swal({
					title: this.sweetAlertTitle,
					text: this.sweetAlert,
					type: this.sweetAlertClass,
					timer: 2000,
					showConfirmButton: false
				}).catch(this.$swal.noop);
            }
        }
    }
</script>
