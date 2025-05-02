@extends('components.master.index')

@push('page-style')
@vite([
])
@endpush

@push('content')

@livewire('admin.role.permission-modal')

@endpush

@push('page-script')
<script type="module">
    $(document).ready(function() {
        $(document).on('click', '[data-kt-action="edit_permission"]', function() {
            const dataId = $(this).attr('data-id');

            Livewire.dispatch('editPermission', [dataId]);
        });
    });

    $(document).on('click', '.form-check-input', function() {
        var modalPermission = document.getElementById('modalPermission');
        modalPermission.addEventListener('hidden.bs.modal', function () {
            location.reload();
        });
    });
</script>
@endpush
