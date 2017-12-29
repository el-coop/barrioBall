<script>
	export default {
		props: {
			translate: {
				required: true,
				type: Object
			}
		},

		data() {
			return {
				userTableFields: [
					{
						name: 'username',
						title: this.translate.username,
						dataClass: 'align-middle',
					},
					{
						name: 'email',
						title: this.translate.email,
						dataClass: 'align-middle',
					}
				],
				matchTableFields: [
					{
						name: 'name',
						title: this.translate.name,
						dataClass: 'align-middle'
					},
					{
						name: 'date_time',
						title: this.translate.time,
						dataClass: 'align-middle',
						sortField: 'date_time'
					},
					{
						name: 'url',
						title: this.translate.view,
						dataClass: 'align-middle',
						callback: this.link,
					},
				],
			}
		},

		methods: {
			link(value) {
				return "<a href='" + value + "'><button class='btn btn-dark'><i class='fa fa-search' ></i></button></a>";
			}
		}
	}
</script>
