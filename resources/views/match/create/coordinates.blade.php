@if ($errors->has('lat') || $errors->has('lng'))
    <div class="alert alert-danger">
        @lang('match/create.coordinateError')
    </div>
@endif
<input name="lat" id="lat" v-model="lat" hidden value="{{ old('lat') }}">
<input name="lng" id="lng" v-model="lng" hidden value="{{ old('lng') }}">