<div class="form-group">
    <label for="{{$field['name']}}">
        <strong>{{ $field['label'] }}:</strong>
    </label>
    <input id="{{$field['name']}}" type="{{ $field['type'] ?? 'text' }}"
           class="form-control{{ $errors->has($field['name']) ? ' is-invalid' : '' }}"
           name="{{$field['name']}}" value="{{ $field['value'] }}" required>

    @if ($errors->has($field['name']))
        <span class="invalid-feedback">
            {{ $errors->first($field['name']) }}
        </span>
    @endif
</div>