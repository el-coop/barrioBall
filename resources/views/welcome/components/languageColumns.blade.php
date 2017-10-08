@foreach($languages as $lang => $language)
    @if($loop->index % ($loop->count/2) == 0)
        <div class="col-12 col-md-6">
            <ul class="list-unstyled mb-0">
                @endif
                <li>
                    <a href="{{action ('LanguageController@switchLang', $lang) }}">{{$language}}</a>
                </li>
                @if($loop->index % ($loop->count/2) == (($loop->count/2) - 1))
            </ul>
        </div>
    @endif
@endforeach