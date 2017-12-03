<div class="row mb-4 mt-3">
    <div class="col-12 col-md-10 mx-auto">
        <h3 class="text-center">
            {{isset($match) ? __('match/edit.title', ['name' => $match->name]) : __('match/create.create') }}
        </h3>
        <form method="post"
              action="{{ isset($match) ? action('Match\MatchController@edit', $match) : action('Match\MatchController@create')}}">
            {{ csrf_field() }}
            @if(isset($match))
                {{ method_field('patch') }}
            @endif
            @include('match.create.coordinates')
            @include('match.create.name')
            <div v-if="! lat" class="text-center">
                <span v-if="! calcAddress">
                    <h3 class="d-inline align-middle">
                        @lang('match/create.chooseOnMap')
                    </h3>
                    <i class="fa fa-info-circle"
                       title="@lang('match/create.locationTooltip')"
                       data-trigger="click"
                       data-toggle="tooltip"></i>
                </span>
                <i class="fa fa-spin fa-spinner fa-3x" v-else></i>
            </div>
            <div class="form-row" v-else>
                @include('match.create.address')
                @include('match.create.dateAndTime')
                @include('match.create.playersAndDescription')
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary btn-block">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
