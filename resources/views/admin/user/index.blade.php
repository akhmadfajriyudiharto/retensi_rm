@extends('components.master.index')

@push('page-style')
@vite([
])
@endpush

@push('content')

@livewire('admin.user.role-modal')

@endpush

@push('page-script')
<script type="module">
    $(document).ready(function() {
        $(document).on('click', '[data-kt-action="edit_role"]', function() {
            const dataId = $(this).attr('data-id');

            Livewire.dispatch('editRole', [dataId]);
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
