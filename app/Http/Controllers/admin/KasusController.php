<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kasus;
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
            'aktif_rj' => [
                'name'      => 'Masa Aktif Rawat Jalan (Tahun)',
                'type'      => 'text',
                'rule'      => 'required|numeric|max:100'
            ],
            'inaktif_rj' => [
                'name'      => 'Masa Inaktif Rawat Jalan (Tahun)',
                'type'      => 'text',
                'rule'      => 'required|numeric|max:100'
            ],
            'aktif_ri' => [
                'name'      => 'Masa Aktif Rawat Inap (Tahun)',
                'type'      => 'text',
                'rule'      => 'required|numeric|max:100'
            ],
            'inaktif_ri' => [
                'name'      => 'Masa Inaktif Rawat Inap (Tahun)',
                'type'      => 'text',
                'rule'      => 'required|numeric|max:100'
            ],
            'deskripsi' => [
                'name'      => 'Informasi Lain',
                'type'      => 'text',
                'rule'      => 'string|max:255',
                'isTable'    => false
            ],
        ];
        $pageSetting = [
            'title'         => 'List Kasus',
            'model'         => \App\Models\Kasus::class,
            'routeName'     => 'admin.kasus',
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $data = Kasus::select(['id', 'nama', 'aktif_rj', 'inaktif_rj', 'aktif_ri', 'inaktif_ri', 'deskripsi']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('aktif_rj', function ($row) {
                    return $row->aktif_rj . ' Tahun';
                })
                ->editColumn('inaktif_rj', function ($row) {
                    return $row->inaktif_rj . ' Tahun';
                })
                ->editColumn('aktif_ri', function ($row) {
                    return $row->aktif_ri . ' Tahun';
                })
                ->editColumn('inaktif_ri', function ($row) {
                    return $row->inaktif_ri . ' Tahun';
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
