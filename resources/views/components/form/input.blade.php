@props(['disabled' => false, 'label' => null, 'name' => null, 'type' => 'text', 'path' => null])
<div class="mb-3">
    @if(isset($label))
        <x-form.label for="{{ $name }}" class="form-label" value="{{ $label }}" />
    @endif
    @if ($path)
        <a class="mb-4" href="{{ asset('storage/' . $path) }}" target="__blank">Lihat</a> |
        <a href="javascript:void(0);" data-kt-action="delete_file" data-id="{{$name}}">Hapus</a>
    @endif

    <input
        type={{$type}}
        {{ $disabled ? 'disabled' : '' }}
        class="form-control {{ $class ?? '' }} @error($name) is-invalid @enderror"
        {{ $attributes }}>

    @if($errors->has($name))
        <x-form.input-error for="{{ $name }}" />
    @endif
</div>
