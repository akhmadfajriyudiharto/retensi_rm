@extends('components.master.index')

@push('page-style')
@vite([
])
@endpush

@push('content')

@livewire('admin.berita-acara.saksi-modal')
@livewire('admin.berita-acara.pilih-rekam-medis-modal')
@livewire('admin.berita-acara.upload-bukti-modal')

@endpush

@push('page-script')
<script type="module">
    $(document).ready(function() {
        Livewire.on('error', message => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                showCancelButton: false,
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-danger',
                }
            });
        });
        $(document).on('click', '[data-kt-action="upload_bukti"]', function() {
            const dataId = $(this).attr('data-id');

            Livewire.dispatch('uploadBukti', [dataId]);
        });

        $(document).on('click', '[data-kt-action="edit_saksi"]', function() {
            const dataId = $(this).attr('data-id');

            Livewire.dispatch('editSaksi', [dataId]);
        });

        $(document).on('click', '[data-kt-action="pilih_rm"]', function() {
            const dataId = $(this).attr('data-id');

            Livewire.dispatch('bukaModalPilihRM', [dataId]);
        });

        $(document).on('click', '[data-kt-action="kunci_ba"]', function() {
            const dataId = $(this).attr('data-id');

            Swal.fire({
                title: 'Apa anda yakin akan mengunci?',
                text: "Setelah dikunci, berita acara tidak dapat diubah kembali.",
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
                    Livewire.dispatch('kunciBA', [dataId]);
                }
            });
        });

        $(document).on('click', '[data-kt-action="buka_ba"]', function() {
            const dataId = $(this).attr('data-id');

            Swal.fire({
                title: 'Apa anda yakin akan membuka?',
                text: "Setelah dibuka, berita acara dapat diubah kembali.",
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
                    Livewire.dispatch('bukaBA', [dataId]);
                }
            });
        });

        $(document).on('click', '[data-kt-action="musnahkan"]', function() {
            const dataId = $(this).attr('data-id');

            Swal.fire({
                title: 'Apa anda yakin akan memusnahkan?',
                text: "Setelah dimusnahkan, data tidak akan kembali.",
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
                    Livewire.dispatch('musnahkan', [dataId]);
                }
            });
        });

        $(document).on('click', '[data-action="delete_saksi"]', function() {
            const dataId = $(this).attr('data-id');

            Swal.fire({
                title: 'Apa anda yakin akan menghapus?',
                text: "Data saksi akan dihapus secara permanen.",
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
                    Livewire.dispatch('deleteSaksi', [dataId]);
                }
            });
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
