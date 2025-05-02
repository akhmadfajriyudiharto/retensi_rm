<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
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
            'email' => [
                'name'      => 'Email',
                'type'      => 'text',
                'rule'      => 'required|email|max:255|unique:users,email'
            ],
            'role' => [
                'name'      => 'Role',
                'type'      => 'text',
                'isEdit'    => false,
                'isCreate'  => false
            ],
            'password' => [
                'name'      => 'Password',
                'type'      => 'password',
                'rule'      => 'required|min:6',
                'processor' => [Hash::class, 'make'],
                'isTable'   => false,
                'isSearch'  => false,
                'isEdit'    => false
            ]
        ];
        $pageSetting = [
            'title'         => 'List User',
            'model'         => \App\Models\User::class,
            'routeName'     => 'admin.users',
            'fields'        => $fields
        ];
        if (request()->ajax()) {
            $users = User::with('roles')
                        ->select(['id', 'name', 'email'])
                        ->when(request()->has('search') && request()->search['value'], function ($query) {
                            $searchValue = request()->search['value']; // Ambil query pencarian
                            $query->whereHas('roles', function ($query) use ($searchValue) {
                                $query->where('name', 'like', '%' . $searchValue . '%');
                            });
                        })->get();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function($row) {
                    return $row->roles->pluck('name')->map(function($role) {
                        return '<span class="badge bg-label-primary">' . $role . '</span>';
                    })->join(' ');
                })
                ->addColumn('action', function($row){
                    $id = $row->id;
                    $frontButton = '<button class="btn btn-icon btn-text-info" data-bs-toggle="modal" data-bs-target="#modalRole" data-kt-action="edit_role" data-id="' . $id . '"><i class="tf-icons ti ti-settings scaleX-n1-rtl ti-xs"></i></button>';
                    return view('components.master.table-button', compact('id', 'frontButton'));
                })
                ->rawColumns(['action','role'])
                ->make(true);
        }
        return view('admin.user.index', compact('pageSetting'));
    }
}
