<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use Yajra\DataTables\Facades\DataTables;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = [
            'kode_dokter' => [
                'name'      => 'Kode Dokter',
                'type'      => 'text',
                'rule'      => 'required|string|max:20|unique:dokters,kode_dokter'
            ],
            'nama' => [
                'name'      => 'Nama',
                'type'      => 'text',
                'rule'      => 'required|string|max:100'
            ],
            'gelar_depan' => [
                'name'      => 'Gelar Depan',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:50',
                'isTable'   => false
            ],
            'gelar_belakang' => [
                'name'      => 'Gelar Belakang',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:50',
                'isTable'   => false
            ],
            'jenis_kelamin' => [
                'name'      => 'Jenis Kelamin',
                'type'      => 'select',
                'rule'      => 'required',
                'options'   => ['L' => 'Laki-laki', 'P' => 'Perempuan']
            ],
            'tanggal_lahir' => [
                'name'      => 'Tanggal Lahir',
                'type'      => 'date',
                'rule'      => 'required'
            ],
            'alamat' => [
                'name'      => 'Alamat',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:255',
                'isTable'   => false
            ],
            'telepon' => [
                'name'      => 'Telepon',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:20|regex:/^[0-9]+$/',
                'isTable'   => false
            ],
            'email' => [
                'name'      => 'Email',
                'type'      => 'text',
                'rule'      => 'nullable|email:rfc,dns|max:50',
                'isTable'   => false
            ],
        ];
        $pageSetting = [
            'title'         => 'List Dokter',
            'model'         => \App\Models\Dokter::class,
            'routeName'     => 'admin.dokter',
            'formWidth'     => 'fullscreen',
            'fieldWidth'    => 6,
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $data = Dokter::select(['dokters.id', 'dokters.kode_dokter', 'dokters.nama', 'dokters.kode_dokter', 'dokters.jenis_kelamin', 'dokters.tanggal_lahir', 'dokters.alamat', 'dokters.telepon', 'dokters.email']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('jenis_kelamin', function ($row) {
                    return $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                })
                ->editColumn('tanggal_lahir', function ($row) {
                    return \Carbon\Carbon::parse($row->tanggal_lahir)->translatedFormat('d F Y');
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
