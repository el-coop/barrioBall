sweet-alert="{{ Session::get('alert',null) ?? $errors->first() }}"
sweet-alert-class="{{ Session::has('alert') ? 'success' : 'error' }}"
sweet-alert-title="{{ Session::has('alert') ? __('global.success') : __('global.error') }}"