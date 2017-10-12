<script>
	import swal from '../../Mixins/swal.vue'
	export default{
		mixins: [swal],
        data(){
            return{
            	message: '',
                userId: 0,
                modal: {

                }
            }
        },

        methods: {
        	limitMessage(){
        		if(this.message.length > 500){
        			this.message = this.message.substr(0,500);
                }
            },

			toggleModal(modal){

        		if(_.isString(modal)){
					this.$refs[modal].toggleModal();
					return;
        		}

				this.modal = modal;
				this.$refs.modal.toggleModal();

            }
        }
    }
</script>
