@extends('layouts.layoutMaster')

@section('title', $pageSetting['title'])

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/leaflet/leaflet.scss',
  'resources/assets/vendor/libs/leaflet/leaflet-draw.scss',
  'resources/assets/vendor/libs/ckeditor5/ckeditor5.scss'
])
@endsection

@section('page-style')
@vite([
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@php
    $vendorJs = [
        'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
        'resources/assets/vendor/libs/select2/select2.js',
        'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
        'resources/assets/vendor/libs/leaflet/leaflet.js',
        'resources/assets/vendor/libs/leaflet/leaflet-draw.js',
        'resources/assets/vendor/libs/ckeditor5/ckeditor5.js'
    ];
    if (isset($pageSetting['isTree']) && $pageSetting['isTree']) {
        $vendorJs[] = 'resources/assets/vendor/libs/@kanety/jquery-simple-tree-table.js';
    }
@endphp
@vite($vendorJs)
@endsection

@php
$tableFields = array_filter($pageSetting['fields'], function ($item) {
    return !isset($item['isTable']) || $item['isTable'] !== false;
});
$searchFields = array_filter($pageSetting['fields'], function ($item) {
    return !isset($item['isSearch']) || $item['isSearch'] !== false;
});
@endphp

@section('page-script')
@endsection

@section('content')
    <x-master.table
        :tableFields="$tableFields"
        :searchFields="$searchFields"
        :routeName="$pageSetting['routeName']"
        :title="$pageSetting['title']"
        :isTree="$pageSetting['isTree'] ?? false"
        :pageDisplay="$pageSetting['pageDisplay'] ?? 10" />

    @livewire('master.form-modal',
    [
        'setting' => [
            'title' => $pageSetting['title'],
            'description' => $pageSetting['description'] ?? null,
            'formWidth' => $pageSetting['formWidth'] ?? null,
            'fieldWidth' => $pageSetting['fieldWidth'] ?? null,
            'model' => $pageSetting['model'],
            'fields' => $pageSetting['fields']]
    ])
@endsection
