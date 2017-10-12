<div class="form-row">
    <div class="form-group col-12">
        <label for="name">@lang('match/create.name')</label>
        <input type="text" id="name" name="name"
               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
               value="{{ old('name') }}" required>
        @if ($errors->has('name'))
            <div class="invalid-feedback">
                {{ $errors->first('name') }}
            </div>
        @endif
    </div>
</div>
