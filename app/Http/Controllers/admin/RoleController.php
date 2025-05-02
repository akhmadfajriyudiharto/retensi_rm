<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = [
            'name' => [
                'name'      => 'Nama',
                'type'      => 'text',
                'rule'      => 'required|string|max:255'
            ],
            'guard_name' => [
                'name'      => 'Guard',
                'type'      => 'text',
                'rule'      => 'required|string|max:255',
                'isCreate'  => false,
                'isEdit'    => false
            ]
        ];
        $pageSetting = [
            'title'         => 'List Role',
            'model'         => \Spatie\Permission\Models\Role::class,
            'routeName'     => 'admin.roles',
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $data = Role::select(['id', 'name', 'guard_name']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $id = $row->id;
                    $frontButton = Auth::user()->can(Route::currentRouteName().'.update')
                    ? '<button class="btn btn-icon btn-text-info" data-bs-toggle="modal" data-bs-target="#modalPermission" data-kt-action="edit_permission" data-id="' . $id . '"><i class="tf-icons ti ti-settings scaleX-n1-rtl ti-xs"></i></button>' : '';
                    return view('components.master.table-button', compact('id','frontButton'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.role.index', compact('pageSetting'));
    }
}
