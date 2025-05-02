<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Yajra\DataTables\Facades\DataTables;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = [
            'nama' => [
                'name'      => 'Jenis Layanan',
                'type'      => 'text',
                'rule'      => 'required|string|max:255'
            ],
            'deskripsi' => [
                'name'      => 'Informasi Lain',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:255'
            ],
        ];
        $pageSetting = [
            'title'         => 'List Layanan',
            'model'         => \App\Models\Layanan::class,
            'routeName'     => 'admin.layanan',
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $data = Layanan::select(['id', 'nama', 'deskripsi']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $id = $row->id;
                    return view('components.master.table-button', compact('id'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('components.master.index', compact('pageSetting'));
    }
}
