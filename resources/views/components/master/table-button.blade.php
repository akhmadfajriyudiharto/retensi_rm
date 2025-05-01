
@php
use Illuminate\Support\Facades\Route;
@endphp

<div class="d-flex align-items-center">
    {{-- <a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-md"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="javascript:;" class="dropdown-item" data-bs-toggle="modal"  data-bs-target="#modalForm" data-kt-action="edit_row" data-id="{{$id}}">Edit</a>
        <a href="javascript:;" class="dropdown-item">Suspend</a>
    </div> --}}
    @if (isset($frontButton))
        {!! $frontButton !!}
    @endif
    @can(Route::currentRouteName() . '.update')
    <button class="btn btn-icon btn-text-primary" data-bs-toggle="modal" data-bs-target="#modalForm" data-kt-action="edit_row" data-id="{{$id}}"><i class="tf-icons ti ti-edit scaleX-n1-rtl ti-xs"></i></button>
    @endcan
    @can(Route::currentRouteName() . '.delete')
    <button class="btn btn-icon btn-text-danger" data-kt-action="delete_row" data-id="{{$id}}"><i class="tf-icons ti ti-trash scaleX-n1-rtl ti-xs"></i></button>
    @endcan
    @if (isset($backButton))
        {!! $backButton !!}
    @endif
</div>
