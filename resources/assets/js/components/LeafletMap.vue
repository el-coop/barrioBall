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
		props: {},

		data(){
			return {
				map: null,
				markers: []
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
			}
		},

		mounted(){
			this.map = L.map(this.$el).locate({
				setView: true,
				maxZoom: 13
			});

			L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="//www.openstreetmap.org/copyright">OpenStreetMap</a>',
				subdomains: ['a', 'b', 'c'],
				interactive: true,
			}).addTo(this.map);

		}

	}
</script>
