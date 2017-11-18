<h2>
    {{ $match->name }}
</h2>
@lang('match/show.managedBy'):
@foreach($managers as $manager)
    <a href="#">{{ $manager->username }}</a>@if (!$loop->last), @endif
@endforeach