<style scoped lang="scss">
    .card-header > a {
        color: inherit;
    }

    .card-img-top{
        max-height: 150px;

        @media only screen and (min-width : 768px){
            max-height: 100px;
        }
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

        methods: {
			chosen(title){
				this.menuActive = title;
				this.$refs.mobileView.toggle(1);
            }
        }
	}
</script>
