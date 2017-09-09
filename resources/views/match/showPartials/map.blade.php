<div class="col row-map-wrapper">
    <leaflet-map ref="map"
                 :center="[{{$match->lat}},{{$match->lng}}]"
                 :init-markers="[[{{$match->lat}},{{$match->lng}}]]"
                 :zoom="15">
    </leaflet-map>
</div>
