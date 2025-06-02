@props(['routeName' => null, 'tableFields' => [], 'title' => null, 'isTree' => null, 'pageDisplay' => null])

@php
use Illuminate\Support\Facades\Route;
@endphp

<script type="module">
    var tableId = $('#datatable');

    window.datatable = tableId.DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: true,
        ajax: {
            url: '{{ route($routeName) }}',
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            @foreach ($tableFields as $key => $item)
                @if (isset($item['name']))
                    @if (isset($item['isTable']) && $item['isTable'] === 'invisible')
                        { data: '{{$key}}', name: '{{$key}}', visible: false },
                    @else
                        { data: '{{$key}}', name: '{{$key}}' },
                    @endif
                @endif
            @endforeach
            { data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        @if(isset($isTree) && $isTree)
        drawCallback: function( settings ) {
            $('#datatable').simpleTreeTable({
                iconPosition: 'td:nth-child(2)',
                // iconTemplate: '<span class="ti ti-circle ti-sm" />',
                opened:'all'
            });
        },
        @endif
        dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-6 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: {{$pageDisplay}},
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        language: {
            paginate: {
                next: '<i class="ti ti-chevron-right ti-sm"></i>',
                previous: '<i class="ti ti-chevron-left ti-sm"></i>'
            }
        },
        buttons: [
            {
                text: '<i class="tf-icons ti ti-search scaleX-n1-rtl ti-sm"></i><span class="d-none d-sm-inline-block">Filter</span>',
                className: 'btn btn-primary waves-effect waves-light me-4',
                attr: {
                    'data-bs-toggle': "collapse",
                    'href':"#collapseSearch",
                    'role':"button",
                    'aria-expanded':"false",
                    'aria-controls':"collapseSearch"
                }
            },
            {
                extend: 'collection',
                className: 'btn btn-label-primary dropdown-toggle me-4 waves-effect waves-light border-none',
                text: '<i class="ti ti-file-export ti-xs me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                buttons: [
                    {
                        extend: 'print',
                        text: '<i class="ti ti-printer me-1" ></i>Print',
                        className: 'dropdown-item',
                    },
                    {
                        extend: 'csv',
                        text: '<i class="ti ti-file-text me-1" ></i>Csv',
                        className: 'dropdown-item',
                    },
                    {
                        extend: 'excel',
                        text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
                        className: 'dropdown-item',
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="ti ti-file-description me-1"></i>Pdf',
                        className: 'dropdown-item',
                    },
                    {
                        extend: 'copy',
                        text: '<i class="ti ti-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                    }
                ]
            },
            @can(Route::currentRouteName() . '.create')
            {
                text: '<i class="tf-icons ti ti-plus scaleX-n1-rtl ti-sm"></i><span class="d-none d-sm-inline-block">Tambah</span>',
                className: 'btn btn-primary',
                attr: {
                    'data-bs-toggle': "modal",
                    'data-bs-target': "#modalForm",
                    'data-kt-action': "add_row"
                }
            },
            @endcan
        ],
        searchBuilder: true
    });
    $('div.head-label').html('<h5 class="card-action-title mb-0">{{$title}}</h5>');

    function filterColumn(name, val) {
        tableId.DataTable().column(name+':name').search(val, false, true).draw();
    }

    $('input.dt-input').on('keyup', function () {
        filterColumn($(this).attr('column-name'), $(this).val());
    });

    $(document).ready(function() {
        $(document).on('click', '[data-kt-action="edit_row"]', function() {
            const dataId = $(this).attr('data-id');

            Livewire.dispatch('edit', [dataId]);
        });

        $(document).on('click', '[data-kt-action="delete_row"]', function() {
            const dataId = $(this).attr('data-id');

            Swal.fire({
                text: 'Apa anda yakin akan menghapus?',
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
                    Livewire.dispatch('delete', [dataId]);
                }
            });
        });

        $('[data-kt-action="add_row"]').click(function(){
            Livewire.dispatch('create');
        });

    });
</script>
