<script>
    export default{

    	data(){
    		return {
    			matches: null,
                searchParams: null
            }
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

			markerHover(index){
				$('#search-results-container').find('.selected').removeClass('selected');
				let selected = $(this.$refs['result' + index]);
				$('html, body').animate({
					scrollTop: (selected.offset().top)
				},500);
				selected.addClass('selected');
			},

			resultHover(index){
				$('.icon-result-' + index).addClass('selected');
			},

			stopHover(index){
				$('.icon-result-' + index).removeClass('selected');
			},

            searchResults(results,params){
				this.searchParams = params;
    	    	let map = this.$refs.map;
    	    	map.clearMarkers();
                this.matches = results;
                $.each(results,(index,value) => {
                	let marker = new L.marker([value.lat,value.lng], { icon : L.icon({
						iconUrl: '/images/match-icon.png',
						iconSize: [30, 30],
						className: 'search-icon icon-result-' + index
					})});
                	marker.on('click',this.markerHover.bind(this,index));
                	map.addMarker(marker);
                });
            }
        }
    }
</script>
