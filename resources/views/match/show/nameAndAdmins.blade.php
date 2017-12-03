<h2>
    {{ $match->name }}
    @can('manage', $match)
        <a href="{{action('Match\MatchController@showEditForm', $match)}}">
            <button class="btn btn-dark rounded-circle">
                <i class="fa fa-edit"></i>
            </button>
        </a>
    @endcan
</h2>
@lang('match/show.managedBy'):
@foreach($managers as $manager)
    <a href="#">{{ $manager->username }}</a>@if (!$loop->last), @endif
@endforeach