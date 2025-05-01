
<div class="mb-1 d-flex justify-content-end align-items-baseline">
    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary']) }}>
    {{ $slot }}
    </button>
</div>
