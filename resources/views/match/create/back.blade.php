<leaflet-map ref="map"
             @right-click="choosenLocation"
        {{ old('lat') && old('lng') ? ':init-markers=[[' . old('lat') . ',' . old('lng') . ']]' : '' }}
        {{ old('lat') && old('lng') ? ':center=[' . old('lat') . ',' . old('lng') . ']' : '' }}
        {{ old('lat') && old('lng') ? ':zoom=15' : '' }}>
</leaflet-map>