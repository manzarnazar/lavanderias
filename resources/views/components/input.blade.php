<div class="form-group">

    <input
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ $value ?? old($name) }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    @if($required) required @endif
/>

    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
