<script>
	import swal from '../../Mixins/swal.vue'

	export default {
		mixins: [swal],
		props: {
			titles: {
				type: Object,
				required: true
			}
		},

		data() {
			return {
				playedMatchesFields: [
					{
						name: 'name',
						title: this.titles.name,
						dataClass: 'align-middle'
					},
					{
						name: 'date_time',
						title: this.titles.date,
						dataClass: 'align-middle',
						sortField: 'date_time'
					},
					{
						name: 'url',
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
						sortField: 'join_requests_count',
						title: this.titles.requests,
						dataClass: 'text-center align-middle'
					},
					{
						name: 'url',
						title: this.titles.view,
						dataClass: 'text-center',
						callback: this.link,
					},
				],
				managedSortOrder: [
					{
						field: 'join_requests_count',
						direction: 'desc'
					}
				],
				playedSortOrder: [
					{
						field: 'date_time',
						direction: 'asc'
					}
				]
			}
		},

		methods: {
			link(value) {
				return "<a href='" + value + "'><button class='btn btn-dark'><i class='fa fa-search' ></button></i></a>";
			}
		}

	}
</script>
