<form method="post" action="{{ $url }}">
    {{ csrf_field() }}
    {{ method_field('patch') }}
    <h4 class="">
        {{ $title }}
    </h4>
    <hr>
    @foreach($fields as $field)
        @if(($field['type'] ?? 'text') == 'languages')
            @component('user.profile.component.languages',[
                'field' => $field
            ])
            @endcomponent
        @else
            @component('user.profile.component.textInput',[
                'field' => $field
            ])
            @endcomponent
        @endif
    @endforeach
    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            {{ $buttonText }}
        </button>
    </div>
</form>