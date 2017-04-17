<script>
	export default{
		props: {
		    deleteUrl: {
		    	type: String,
                required: true
            }
        },

		data(){
			return {
				jsErrorFields: [
					{
						name: 'class',
						title: 'Error',
						sortField: 'class'
					},
					{
						name: 'error.page',
						title: 'URL',
					},
					{
						name: 'error.user',
						title: 'User'
					},
					{
						name: 'created_at',
						title: 'Date and Time',
						sortField: 'created_at'

					},
					{
						name: '__slot:actions',
						title: 'Actions',
						dataClass: 'text-center'
					}

				],
				phpErrorFields: [
					{
						name: 'message',
						title: 'Error',
						sortField: 'message'
                    },
					{
						name: 'error.page',
						title: 'URL'
					},
					{
						name: 'error.user',
						title: 'User'
					},
					{
						name: 'created_at',
						title: 'Date and Time',
						sortField: 'created_at'
					},
                    {
						name: '__slot:actions',
						title: 'Resolve',
						dataClass: 'text-center'
					}
				],
			}
		},

        methods: {
			onDelete(args){
				this.$refs.phpTable.tableLoading();
				this.$refs.jsTable.tableLoading();
				axios.delete(this.deleteUrl + '/' + args.data.error.id).then(() =>{
					this.$refs.phpTable.updateParams();
    				this.$refs.jsTable.updateParams();
                });
            }
        }
	}
</script>
