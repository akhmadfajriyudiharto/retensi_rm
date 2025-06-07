@extends('components.master.index')

@push('page-style')
@vite([
])
@endpush

@push('content')

@livewire('admin.pasien.rekam-medis-modal')

@endpush

@push('page-script')
<script type="module">
    $(document).ready(function() {
        $(document).on('click', '[data-kt-action="rekam_medis"]', function() {
            const dataId = $(this).attr('data-id');

            Livewire.dispatch('viewRekamMedis', [dataId]);
        });
    });

    $(document).on('click', '.form-check-input', function() {
        var modalRole = document.getElementById('modalRole');
        modalRole.addEventListener('hidden.bs.modal', function () {
            location.reload();
        });
    });
</script>
@endpush
