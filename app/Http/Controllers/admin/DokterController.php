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
                'rule'      => 'required|string|max:20|unique:dokters,kode_dokter',
                'isTable'   => 'invisible'
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
                'isTable'   => 'invisible'
            ],
            'gelar_belakang' => [
                'name'      => 'Gelar Belakang',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:50',
                'isTable'   => 'invisible'
            ],
            'spesialis' => [
                'name'      => 'Spesialis',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:100'
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
                'rule'      => 'required',
                'isSearch'  => false
            ],
            'alamat' => [
                'name'      => 'Alamat',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:255',
                'isTable'   => 'invisible'
            ],
            'telepon' => [
                'name'      => 'Telepon',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:20|regex:/^[0-9]+$/',
                'isTable'   => 'invisible'
            ],
            'email' => [
                'name'      => 'Email',
                'type'      => 'text',
                'rule'      => 'nullable|email:rfc,dns|max:50',
                'isTable'   => 'invisible'
            ],
            'kontak' => [
                'name'      => 'Kontak',
                'type'      => 'text',
                'isEdit'    => false,
                'isCreate'  => false
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
            $data = Dokter::select(['dokters.id', 'dokters.kode_dokter', 'dokters.nama', 'dokters.gelar_depan', 'dokters.gelar_belakang', 'dokters.kode_dokter', 'dokters.spesialis', 'dokters.jenis_kelamin', 'dokters.tanggal_lahir', 'dokters.alamat', 'dokters.telepon', 'dokters.email']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('nama', function ($row) {
                    return $row->kode_dokter . '<br/>' . $row->gelar_depan . ' '. $row->nama . ', ' . $row->gelar_belakang;
                })
                ->editColumn('jenis_kelamin', function ($row) {
                    return $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                })
                ->editColumn('tanggal_lahir', function ($row) {
                    return \Carbon\Carbon::parse($row->tanggal_lahir)->translatedFormat('d F Y');
                })
                ->addColumn('kontak', function ($row) {
                    return 'Telepon: ' . $row->telepon . '<br/>' . 'Email: ' . $row->email . '<br/>' . 'Alamat: ' . $row->alamat;
                })
                ->addColumn('action', function($row){
                    $id = $row->id;
                    return view('components.master.table-button', compact('id'));
                })
                ->rawColumns(['action','nama','kontak'])
                ->make(true);
        }
        return view('components.master.index', compact('pageSetting'));
    }
}
