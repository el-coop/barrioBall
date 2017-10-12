<div class="form-group">
    <select id="language" name='language'
            class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}">
        @foreach (config('languages') as $lang => $language)
            <option value="{{$lang}}"{{ $user->language == $lang ? ' selected' : ''}}>{{ $language }}</option>
        @endforeach
    </select>

    @if ($errors->has('language'))
        <span class="invalid-feedback">
            {{ $errors->first('language') }}
        </span>
    @endif
</div>
