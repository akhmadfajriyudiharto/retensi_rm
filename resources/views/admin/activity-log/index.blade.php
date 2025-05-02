@extends('layouts.layoutMaster')

@section('title', $pageSetting['title'])

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss'
])
@endsection

@section('page-style')
@vite([
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
        'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
])
@endsection

@section('page-script')
<script type="module">
    var tableId = $('#datatable');

    var datatable = tableId.DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route($pageSetting["routeName"]) }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
                { data: 'log_name', name: 'log_name' },
                { data: 'description', name: 'description' },
                { data: 'subject_type', name: 'subject_type' },
                { data: 'subject_id', name: 'subject_id' },
                { data: 'causer_id', name: 'causer_id' },
                { data: 'created_at', name: 'created_at' },
                { data: 'properties', name: 'properties' },
            ],
            order: [[6, 'desc']], // Order by "Created At"
            responsive: true,
            autoWidth: false,
        });
    $('div.head-label').html('<h5 class="card-action-title mb-0">Log Activity</h5>');
</script>
@endsection

@section('content')

<!-- Datatable -->
<div class="card card-action mb-12">
    <div class="card-datatable table-responsive pt-0">
        <div class="head-label"></div>
        <table class="table table-hover" style="width: 100%;" id="datatable">
        <thead>
            <tr>
                <th>No</th>
                <th>Lokasi</th>
                <th>Deskripsi</th>
                <th>Tipe Subjek</th>
                <th>Subjek</th>
                <th>Causer</th>
                <th>Pada</th>
                <th>Properti</th>
            </tr>
        </thead>
        </table>
    </div>
</div>
<!--/ Datatable -->
@endsection
