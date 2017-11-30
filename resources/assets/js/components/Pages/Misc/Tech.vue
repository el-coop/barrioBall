<style scoped>
    .card-header > a {
        color: inherit;
    }
    .card-img-top {
        max-height: 100px;
        max-width: 100%;
        width: auto !important;
    }
</style>

<script>
	export default {
		props: {
		    active:{
		    	required: true,
                type: String
            }
        },
		data() {
			return {
				menuActive: this.active
			}
		},
	}
</script>
