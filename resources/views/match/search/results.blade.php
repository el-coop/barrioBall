<div class="col-12" v-if="matches != null && !matches.length">
    <h4 class="text-center">
        @lang('match/search.noMatchesFound')
    </h4>
</div>
<div class="col-12 col-md-6 mb-3" v-for="(match, index) in matches">
    <div class="card" :class="{ selected: selectedResult == index}"
         :ref="'result' + index" @mouseenter="resultHover(index)"
         @mouseleave="stopHover(index)">
        <div class="card-header bg-white">
            <a :href="match.url">@{{ match.name  }}</a>
            <span class="pull-right">@{{ match.date }} @{{ match.time }}</span>
        </div>
        <div class="card-block search-result-map-wrapper">
            <div class="map-background search-result-map">
                <leaflet-map :interactive="false" :zoom="13" :center="[match.lat,match.lng]" class="opaque">
                </leaflet-map>
                <div class="row">
                    <div class="col-7"><strong>@{{ match.address }}</strong></div>
                    <div class="col-5 text-right">
                        <strong>@{{ match.players ? match.players : '&infin;' }} @lang('match/search.players')</strong></div>
                    <div class="col-12">
                        <p>@{{ match.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
