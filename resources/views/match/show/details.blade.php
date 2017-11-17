<div class="col-12 col-md-4 text-center">
    <p>
        <i class="fa fa-home fa-3x"></i>
    </p>
    <strong>
        {{$match->address}}
    </strong>
    <hr class="d-md-none">
</div>
<div class="col-12 col-md-4 text-center">
    <p>
        <i class="fa fa-clock-o fa-3x"></i>
    </p>
    <strong>
        {{$match->date}} {{$match->time}}
    </strong>
    <hr class="d-md-none">
</div>
<div class="col-12 col-md-4 text-center">
    <p>
        <i class="fa fa-users fa-3x"></i>
    </p>
    <strong>
        {{$match->registeredPlayers->count()}}/{{$match->players}}
    </strong>
</div>