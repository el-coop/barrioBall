<leaflet-map ref="map"
             @right-click="choosenLocation"
        {{ old('lat', $match->lat ?? false) && old('lng', $match->lng ?? false) ? ':init-markers=[[' . old('lat', $match->lat ?? false) . ',' . old('lng', $match->lng ?? false) . ']]' : '' }}
        {{ old('lat', $match->lat ?? false) && old('lng', $match->lng ?? false) ? ':center=[' . old('lat', $match->lat ?? false) . ',' . old('lng', $match->lng ?? false) . ']' : '' }}
        {{ old('lat', $match->lat ?? false) && old('lng', $match->lng ?? false) ? ':zoom=15' : '' }}>
</leaflet-map>