<div class="card text-center{{ ($dark ?? false) ? ' bg-dark text-white' : ''}}" v-if="'{{ $category }}' == menuActive" key="{{ $title }}">
    <div class="card-header"><a href="{{ url($url) }}" target="_blank">{{$title}}</a></div>
    <img class="card-img-top p-2 m-auto" src="{{ asset("images/{$image}") }}" alt="{{ $title }} logo unavailable">
    <div class="card-body text-left">
        <p class="card-text">{{ $description }}</p>
    </div>
    <div class="card-footer">
        <a href="{{ url($url) }}" target="_blank"><button class="btn btn-secondary">Read More</button></a>
    </div>
</div>