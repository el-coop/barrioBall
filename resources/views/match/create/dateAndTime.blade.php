<div class="form-group col-12 col-md-6{{ $errors->has('date') ? ' is-invalid' : '' }}">
    <date-picker label="@lang('match/create.date'):" name="date"
                 init-value="{{ old('date', ($match->date ?? '')) }}" @shown="hideMapToggle = true" @hidden="hideMapToggle = false"></date-picker>
    @if ($errors->has('date'))
        <div class="invalid-feedback">
            {{ $errors->first('date') }}
        </div>
    @endif
</div>
<div class="form-group col-12 col-md-6{{ $errors->has('time') ? ' is-invalid' : '' }}">
    <time-picker label="@lang('match/create.startTime'):" name="time"
                 init-value="{{ old('time', $match->time ?? '') }}" @shown="hideMapToggle = true" @hidden="hideMapToggle = false"></time-picker>
    @if ($errors->has('time'))
        <div class="invalid-feedback">
            {{ $errors->first('time') }}
        </div>
    @endif
</div>
