<script>
	export default {
		props: {
			mapName: {
				type: String,
				default: 'Map'
			},

			createName: {
				type: String,
				default: 'Create'
			},
			initLat: {
				type: String,
				default: null
			},
			initLng: {
				type: String,
				default: null
			},
			initAddress: {
				type: String,
				default: null
			},
            translate: {
				type: Object,
                required: true
            }
		},

		data() {
			return {
				mapToggled: false,
				mapBtn: this.mapName,
                lat: this.initLat,
                lng: this.initLng,
                address: this.initAddress,
				calcAddress: false
			}
		},

		methods: {
			toggleMap() {
				this.mapToggled = !this.mapToggled;
				if (this.mapToggled) {
					this.mapBtn = this.createName;
				} else {
					this.mapBtn = this.mapName;
				}
			},

			choosenLocation(ev) {
				this.toggleMap();
				this.calcAddress = true;
				this.$refs.map.clearMarkers();
				this.$refs.map.addMarker(new L.marker(ev.latlng, {
					icon: L.icon({
						iconUrl: '/images/match-icon.png',
						iconSize: [30, 30]
					})
				}));

				let geocoder = new L.Control.Geocoder.Nominatim();
                geocoder.reverse(ev.latlng,100,(results) => {
                	if(results[0]){
						this.address = results[0].name;
                    } else {
                		this.address = '';
                    }
					this.calcAddress = false;
					this.$swal({
						title: this.translate.confirmAddress,
						input: 'text',
						type: 'question',
						inputValue: this.address,
						confirmButtonText: '<i class="fa fa-check"></i>',
					}).then((text) => {
						this.lat = ev.latlng.lat;
						this.lng = ev.latlng.lng;
						this.address = text;
                    }).catch(this.$swal.noop);
				});

			}
		},

        mounted(){
			$('[data-toggle="tooltip"]').tooltip();
			$('[data-toggle="tooltip"]').on('shown.bs.tooltip',() => {
				$('[data-toggle="tooltip"]').tooltip('update');
            });
        }
	}
</script>
