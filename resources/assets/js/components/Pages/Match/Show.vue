<script>
    export default{
        data(){
            return{
            	message: ''
            }
        },

        methods: {
        	limitMessage(){
        		if(this.message.length > 500){
        			this.message = this.message.substr(0,500);
                }
            }
        }
    }
</script>
