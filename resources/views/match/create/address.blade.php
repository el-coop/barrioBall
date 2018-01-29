<div class="form-group col-12{{ $errors->has('address') ? ' is-invalid' : '' }}">
    <label for="address">@lang('match/create.address')</label>
    <div class="input-group">
        <input type="text" id="address" name="address" v-model="address"
               value="{{ old('address') }}"
               class="form-control" required>
        <div class="input-group-append">
            <span class="input-group-text">
                <i class="fa fa-spinner fa-spin" v-if="calcAddress"></i>
                <i class="fa fa-check" v-else></i>
            </span>
        </div>
    </div>
    @if ($errors->has('address'))
        <div class="invalid-feedback">
            {{ $errors->first('address') }}
        </div>
    @endif
</div>
