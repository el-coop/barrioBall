<script>
	import swal from '../../Mixins/swal.vue'
	export default{
		mixins: [swal],
        data(){
            return{
            	message: '',
                user: '',
                userId: 0
            }
        },

        methods: {
        	limitMessage(){
        		if(this.message.length > 500){
        			this.message = this.message.substr(0,500);
                }
            },

			toggleModal(ref){
        		this.$refs[ref].toggleModal();
            }
        }
    }
</script>
