<template>
    <div class="map">
    </div>
</template>
<style>
    .map {
        width: 100%;
        height: 100%;
    }
</style>
<script>
	export default{
		props: {
			interactive: {
				type: Boolean,
				default: true
			},
			center: {
				type: Array
			},
			zoom: {
				type: Number,
                default: null
			},
        },

		data(){
			return {
				map: null,
				markers: [],
                startPos: null,
			}
		},

		methods: {
			getBounds(){
				return this.map.getBounds();
			},

			addMarker(marker){
				marker.addTo(this.map);
				this.markers.push(marker);
			},


			clearMarkers(){
				//noinspection BadExpressionStatementJS
				$.each(this.markers, (index, value) => {
					this.map.removeLayer(value);
			    });
				this.markers = [];
			},

            disableMapInteraction(){
				this.map.dragging.disable();
				this.map.touchZoom.disable();
				this.map.doubleClickZoom.disable();
				this.map.scrollWheelZoom.disable();
				this.map.boxZoom.disable();
				this.map.keyboard.disable();
				if (this.map.tap) {
					this.map.tap.disable();
				}
				this.map.zoomControl.disable()
				this.$el.style.cursor='default';
            }
		},

		mounted(){
			this.map = L.map(this.$el,{
				center: this.center,
				zoom: this.zoom,
				maxZoom: 13
            });

            if(! this.center){
			    this.map.locate({
					setView: true,
				});
            }

			if(! this.interactive){
				this.disableMapInteraction();
            }

			L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="//www.openstreetmap.org/copyright">OpenStreetMap</a>',
				subdomains: ['a', 'b', 'c'],
				interactive: true,
			}).addTo(this.map);

		}

	}
</script>
