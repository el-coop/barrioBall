@foreach($buttons as $button)
    <div class="col-12 col-md-{{ 12/$loop->count }}{{ $loop->first ? '' : ' mt-1 mt-md-0' }}">
        <a href="{{ $button['url'] }}" class="btn btn-block {{ $button['class'] }} btn-lg">
            {{ $button['text'] }}
        </a>
    </div>
@endforeach