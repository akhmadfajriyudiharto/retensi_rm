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
                'rule'      => 'required|string|max:20|unique:pasiens,no_rm'
            ],
            'nama' => [
                'name'      => 'Nama',
                'type'      => 'text',
                'rule'      => 'required|string|max:255'
            ],
            'nik' => [
                'name'      => 'NIK',
                'type'      => 'text',
                'rule'      => 'required|digits_between:16,17'
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
