@extends('components.master.index')

@push('page-style')
@vite([
])
@endpush

@push('content')

@livewire('admin.retensi.retensi-modal')

@endpush

@push('page-script')
<script type="module">
    $(document).ready(function() {
        $(document).on('click', '[data-kt-action="retensi_row"]', function() {
            const dataId = $(this).attr('data-id');

            Swal.fire({
                text: 'Apa anda yakin akan meretensi data ini?',
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('retensi', [dataId]);
                }
            });
        });
    });
</script>
@endpush
