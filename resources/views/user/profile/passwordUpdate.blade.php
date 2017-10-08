<form method="post" action="{{ action('UserController@updatePassword') }}">
    {{ csrf_field() }}
    {{ method_field('patch') }}
    <h4>
        @lang('profile/page.changePassword')
    </h4>
    <hr>
    <div class="form-group">
        <label for="password">
            <strong>New @lang('profile/page.password'):</strong>
        </label>
        <input id="password" type="password"
               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
               name="password"
               required>
        @if ($errors->has('password'))
            <span class="invalid-feedback">
                {{ $errors->first('password') }}
            </span>
        @endif
    </div>
    <div class="form-group">
        <label for="password-confirm">
            <strong>@lang('profile/page.confirmPassword'):</strong>
        </label>
        <input id="password-confirm" type="password" class="form-control"
               name="password_confirmation" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            @lang('profile/page.updatePassword')
        </button>
    </div>
</form>