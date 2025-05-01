@props(['disabled' => false, 'label' => null, 'name' => null, 'options' => [], 'selected' => null, 'class' => null])

<div class="mb-3">
    @if(isset($label))
        <x-form.label for="{{ $name }}" class="form-label" value="{{ $label }}" />
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $disabled ? 'disabled' : '' }}
        class="form-select {{ $class ?? '' }} @error($name) is-invalid @enderror"
        {{ $attributes }}>

        <option value="">
            ---- Pilih {{ $label }} ----
        </option>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ $value == $selected ? 'selected' : '' }}>
                {!! $text !!}
            </option>
        @endforeach
    </select>

    @if($errors->has($name))
        <x-form.input-error for="{{ $name }}" />
    @endif
</div>
