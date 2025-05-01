@props(['disabled' => false, 'label' => null, 'name' => null, 'placeholder' => null])

<div class="mb-3">
    @if(isset($label))
        <x-form.label for="{{ $name }}" class="form-label" value="{{ $label }}" />
    @endif

    <input
        type="password"
        name="{{ $name }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $disabled ? 'disabled' : '' }}
        class="form-control {{ $class ?? '' }} @error($name) is-invalid @enderror"
        {{ $attributes }}>

    @if($errors->has($name))
        <x-form.input-error for="{{ $name }}" />
    @endif
</div>
