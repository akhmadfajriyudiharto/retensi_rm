@props(['disabled' => false, 'label' => null, 'name' => null, 'value' => false])

<div class="mb-3 form-check">
    @if(isset($label))
        <x-form.label for="{{ $name }}" class="form-label" value="{{ $label }}" />
    @endif

    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="1"
        {{ $value ? 'checked' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        class="form-check-input {{ $class ?? '' }} @error($name) is-invalid @enderror"
        {{ $attributes }}>

    @if(isset($label))
        <x-form.input-error for="{{ $name }}" />
    @endif
</div>
