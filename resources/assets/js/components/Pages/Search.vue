<script>
    export default{
        methods: {
    	    submit(){
    	    	let bounds = this.$refs.map.getBounds();
                let form = this.$refs.form;

				form.addData('north',bounds.getNorth());
				form.addData('east',bounds.getEast());
				form.addData('west',bounds.getWest());
				form.addData('south',bounds.getSouth());
				form.submit();
            }
        }
    }
</script>
