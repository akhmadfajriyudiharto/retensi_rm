<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kasus;
use App\Models\Layanan;
use Yajra\DataTables\Facades\DataTables;

class KasusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = [
            'nama' => [
                'name'      => 'Jenis Kasus',
                'type'      => 'text',
                'rule'      => 'required|string|max:255'
            ],
            'layanan_id' => [
                'name'      => 'Jenis Layanan',
                'type'      => 'select',
                'rule'      => 'required',
                'isSearch'  => false,
                'options'   => Layanan::pluck('nama', 'id')->all()
            ],
            'aktif' => [
                'name'      => 'Masa Aktif (Tahun)',
                'type'      => 'text',
                'rule'      => 'required|numeric|max:100'
            ],
            'inaktif' => [
                'name'      => 'Masa Inaktif (Tahun)',
                'type'      => 'text',
                'rule'      => 'required|numeric|max:100'
            ],
            'deskripsi' => [
                'name'      => 'Informasi Lain',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:255'
            ],
        ];
        $pageSetting = [
            'title'         => 'List Kasus',
            'model'         => \App\Models\Kasus::class,
            'routeName'     => 'admin.kasus',
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $data = Kasus::select(['kasuses.id', 'kasuses.nama', 'layanans.nama as layanan_id', 'aktif', 'inaktif', 'kasuses.deskripsi'])
                        ->join('layanans', 'layanans.id', 'kasuses.layanan_id');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('aktif', function ($row) {
                    return $row->aktif . ' Tahun';
                })
                ->editColumn('inaktif', function ($row) {
                    return $row->inaktif . ' Tahun';
                })
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
