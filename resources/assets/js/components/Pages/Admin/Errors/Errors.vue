<script>
	export default{
		props: {
		    deleteUrl: {
		    	type: String,
                required: true
            },
			translate: {
				type: Object,
				required: true
			}
        },

		data(){
			return {
				jsErrorFields: [
					{
						name: 'class',
						title: this.translate.error,
						sortField: 'class'
					},
					{
						name: 'error.page',
						title: 'URL',
					},
					{
						name: 'error.user.username',
						title: this.translate.user
					},
					{
						name: 'created_at',
						title: this.translate.date,
						sortField: 'created_at'

					},
					{
						name: '__slot:delete',
						title: this.translate.resolve,
						dataClass: 'text-center'
					}

				],
				phpErrorFields: [
					{
						name: 'message',
						title: this.translate.error,
						sortField: 'message'
                    },
					{
						name: 'error.page',
						title: 'URL'
					},
					{
						name: 'error.user.username',
						title: this.translate.user
					},
					{
						name: 'created_at',
						title: this.translate.date,
						sortField: 'created_at'
					},
                    {
						name: '__slot:delete',
						title: this.translate.resolve,
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
