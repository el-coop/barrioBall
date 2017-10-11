<script>
    import swal from '../../Mixins/swal.vue'

    export default{
    	mixins: [swal],
    	props: {
    	    titles: {
    	    	type: Object,
    	    	required: true
            }
        },

        data(){
            return {
				playedMatchesFields: [
					{
						name: 'name',
						title: this.titles.name,
						dataClass: 'align-middle'
					},
					{
						name: 'id',
						title: this.titles.view,
						dataClass: 'text-center',
						callback: this.link,
					},
				],
				managedMatchesFields: [
					{
						name: 'name',
						title: this.titles.name,
						dataClass: 'align-middle'
					},
                    {
                    	name: 'join_requests_count',
                        title: this.titles.requests,
						dataClass: 'text-center align-middle'
                    },
					{
						name: 'id',
						title: this.titles.view,
						dataClass: 'text-center',
						callback: this.link,
					},
				]
            }
        },

        methods: {
            link (value) {
                return "<a href='/matches/" + value + "'><button class='btn btn-dark'><i class='fa fa-search' ></button></i></a>";
            }
        }

    }
</script>
