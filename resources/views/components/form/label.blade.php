@props(['value'])

<label {{ $attributes }}>
  <b>{{ $value ?? $slot }}</b>
</label>
