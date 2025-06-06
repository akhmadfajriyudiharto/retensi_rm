<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\Layanan;
use Yajra\DataTables\Facades\DataTables;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = [
            'no_rm' => [
                'name'      => 'No Rekam Medis',
                'type'      => 'text',
                'rule'      => 'required|string|max:20|unique:pasiens,no_rm',
                'isTable'   => 'invisible'
            ],
            'nama' => [
                'name'      => 'Nama',
                'type'      => 'text',
                'rule'      => 'required|string|max:255'
            ],
            'nik' => [
                'name'      => 'NIK',
                'type'      => 'text',
                'rule'      => 'required|digits_between:16,17',
                'isTable'   => 'invisible'
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
                'rule'      => 'nullable|string|max:255'
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
        ];
        $pageSetting = [
            'title'         => 'List Pasien',
            'model'         => \App\Models\Pasien::class,
            'routeName'     => 'admin.pasien',
            'formWidth'     => 'fullscreen',
            'fieldWidth'    => 6,
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $data = Pasien::select(['pasiens.id', 'pasiens.no_rm', 'pasiens.nama', 'pasiens.nik', 'pasiens.jenis_kelamin', 'pasiens.tanggal_lahir', 'pasiens.alamat', 'pasiens.telepon', 'pasiens.email']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('nama', function ($row) {
                    return $row->no_rm . '<br/>' . $row->nama . '<br/>' . $row->nik;
                })
                ->editColumn('jenis_kelamin', function ($row) {
                    return $row->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                })
                ->editColumn('tanggal_lahir', function ($row) {
                    return \Carbon\Carbon::parse($row->tanggal_lahir)->translatedFormat('d F Y');
                })
                ->addColumn('action', function($row){
                    $id = $row->id;
                    $frontButton = '<button class="btn btn-icon btn-text-info" data-bs-toggle="modal" data-bs-target="#modalRekamMedis" data-kt-action="rekam_medis" data-id="' . $id . '"><i class="tf-icons ti ti-report scaleX-n1-rtl ti-xs"></i></button>';
                    return view('components.master.table-button', compact('id', 'frontButton'));
                })
                ->rawColumns(['action','nama'])
                ->make(true);
        }
        return view('admin.pasien.index', compact('pageSetting'));
    }
}
