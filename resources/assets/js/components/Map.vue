<template>
    <div style="height: 580px"></div>
</template>
<script>
    export default{
        mounted(){
            let map = L.map(this.$el).setView([-34.600432785044404, -58.43421936035156], 13);

            let myIcon = L.icon({
                iconUrl: '/images/match-icon.png',
                iconSize: [30, 30],
            });
            let matchMarker;

            let geocoder = new L.Control.Geocoder.Nominatim();
            let control = new L.Control.Geocoder({
                geocoder: geocoder,
                position: 'topleft'
            }).addTo(map);

            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                subdomains: ['a', 'b', 'c'],
                interactive: true,
            }).addTo(map);

            map.on('contextmenu', function (ev) {
                if (typeof(matchMarker) === 'undefined') {
                    matchMarker = new L.marker(ev.latlng, {icon: myIcon});
                    matchMarker.addTo(map);
                }
                else {
                    matchMarker.setLatLng(ev.latlng);
                }
                geocoder.reverse(ev.latlng, map.options.crs.scale(map.getZoom()), function(results) {
                    let r = results[0]['name'];
                    let number = r.slice(0, r.search(','));
                    r = r.substr(r.search(',')+2);
                    let street = r.slice(0,r.search(','));
                    let fullAddress = street + ' ' +number;
                    $('#address').val(fullAddress);


                });
                console.log(ev.latlng);
                $('#lat').val(ev.latlng.lat);
                $('#lng').val(ev.latlng.lng);
            });

            $('#mapModal').on('shown.bs.modal', function () {
                map.invalidateSize();
                if (typeof(matchMarker) !== 'undefined'){
                    map.setView(matchMarker._latlng, 13);
                }
            });


        }

    }
</script>
