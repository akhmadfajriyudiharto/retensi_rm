@props(['disabled' => false, 'label' => null, 'name' => null])

<div class="mb-3">
    @if(isset($label))
        <x-form.label for="{{ $name }}" class="form-label" value="{{ $label }}" />
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $disabled ? 'disabled' : '' }}
        class="form-control {{ $class ?? '' }} @error($name) is-invalid @enderror"
        {{ $attributes }}></textarea>

    @if($errors->has($name))
        <x-form.input-error for="{{ $name }}" />
    @endif
</div>
