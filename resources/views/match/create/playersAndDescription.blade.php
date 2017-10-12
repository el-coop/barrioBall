<div class="form-group col-12">
    <label>@lang('match/create.players')</label>
    <select name="players" id="players"
            class="form-control{{ $errors->has('players') ? ' is-invalid' : '' }}"
            required>
        @for ($i = 8; $i<23; $i+=2)
            <option value="{{$i}}">{{$i}}</option>
        @endfor
    </select>
    @if ($errors->has('players'))
        <div class="invalid-feedback">
            {{ $errors->first('players') }}
        </div>
    @endif
</div>
<div class="form-group col-12">
    <label for="description">@lang('match/create.description')</label>
    <textarea id="description" name="description"
              class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
              required>{{ old('description') }}</textarea>
    @if ($errors->has('description'))
        <div class="invalid-feedback">
            {{ $errors->first('description') }}
        </div>
    @endif
</div>
