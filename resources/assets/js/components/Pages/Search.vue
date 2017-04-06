<script>
    export default{
    	props: {
    	    url: {
    	    	type: String,
                default: window.document.URL
            },

			mapName: {
				type: String,
				default: 'Map'
			},

			searchName: {
				type: String,
				default: 'Search'
			}
        },

    	data(){
    		return {
    			matches: null,
                searchParams: null,
                pages: 0,
                mapToggled: false,
				mapBtn: 'Map',
                selectedResult: null
            }
        },

        mounted(){
    	    this.mapBtn = this.mapName;
        },

        methods: {
			submit(){
				let bounds = this.$refs.map.getBounds();
				let form = this.$refs.form;

				form.addData('north',bounds.getNorth());
				form.addData('east',bounds.getEast());
				form.addData('west',bounds.getWest());
				form.addData('south',bounds.getSouth());
				form.submit();
			},
			getPage(page){
				this.selectedResult = null;
				axios.post(this.url + '?page=' + page,this.searchParams).then((response) => {
					this.searchResults(response.data,this.searchParams);
                });
            },

			markerClick(index){
				this.mapToggled = false;
				this.mapBtn = this.mapName;
				this.selectedResult = index;
				let selected = $(this.$refs['result' + index]);
				$('html, body').animate({
					scrollTop: selected.offset().top - $('.navbar').height()
				},500);
			},

			resultHover(index){
				$('.icon-result-' + index).addClass('selected');
			},

			stopHover(index){
				$('.icon-result-' + index).removeClass('selected');
			},

            toggleMap(){
				this.mapToggled = ! this.mapToggled;
				if(this.mapToggled){
					this.mapBtn = this.searchName;;
                } else {
					this.mapBtn = this.mapName;;
                }
            },

            searchResults(results,params){
				this.searchParams = params;
				this.pages = results.last_page;
    	    	let map = this.$refs.map;
    	    	map.clearMarkers();
                this.matches = results.data;
                $.each(results.data,(index,value) => {
                	let marker = new L.marker([value.lat,value.lng], { icon : L.icon({
						iconUrl: '/images/match-icon.png',
						iconSize: [30, 30],
						className: 'search-icon icon-result-' + index
					})});
                	marker.on('click',this.markerClick.bind(this,index));
                	map.addMarker(marker);
                });
            }
        }
    }
</script>
