<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaAcaraPemusnahan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class BeritaAcaraPemusnahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = [
            'tanggal' => [
                'name'      => 'Tanggal',
                'type'      => 'date',
                'rule'      => 'required',
                'isSearch'  => false
            ],
            'ketua' => [
                'name'      => 'Ketua',
                'type'      => 'text',
                'isSearch'  => false,
                'isCreate'  => false,
                'isEdit'    => false
            ],
            'nama_ketua' => [
                'name'      => 'Nama Ketua',
                'type'      => 'text',
                'rule'      => 'required|string|max:255',
                'isTable'   => 'invisible'
            ],
            'nip_ketua' => [
                'name'      => 'NIP Ketua',
                'type'      => 'text',
                'rule'      => 'nullable|digits_between:18,19',
                'isTable'   => 'invisible'
            ],
            'nik_ketua' => [
                'name'      => 'NIK Ketua',
                'type'      => 'text',
                'rule'      => 'required|digits_between:16,17',
                'isTable'   => 'invisible'
            ],
            'alamat_ketua' => [
                'name'      => 'Alamat Ketua',
                'type'      => 'text',
                'rule'      => 'required|string|max:255',
                'isTable'   => 'invisible'
            ],
            'kota_pemusnahan' => [
                'name'      => 'Kota Pemusnahan',
                'type'      => 'text',
                'rule'      => 'required|string|max:50',
                'isTable'   => 'invisible'
            ],
            'tempat_pemusnahan' => [
                'name'      => 'Tempat Pemusnahan',
                'type'      => 'text',
                'rule'      => 'required|string|max:50'
            ],
            'alamat_pemusnahan' => [
                'name'      => 'Alamat Pemusnahan',
                'type'      => 'text',
                'rule'      => 'required|string|max:255',
                'isTable'   => 'invisible',
                'isSearch'  => false
            ],
            'status' => [
                'name'      => 'Status',
                'type'      => 'text',
                'rule'      => 'nullable|string|max:255',
                'isCreate'  => false,
                'isEdit'    => false
            ],
            'file' => [
                'name'      => 'File Bukti',
                'type'      => 'file',
                'isSearch'  => false,
                'isCreate'  => false,
                'isEdit'    => false
            ],
        ];
        $pageSetting = [
            'title'         => 'Berita Acara Pemusnahan',
            'model'         => \App\Models\BeritaAcaraPemusnahan::class,
            'routeName'     => 'admin.laporan-berita-acara',
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $data = BeritaAcaraPemusnahan::select(['berita_acara_pemusnahans.*']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($row) {
                    return \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y');
                })
                ->editColumn('ketua', function ($row) {
                    return 'NIK: ' . $row->nik_ketua . '<br/>NIP: ' . $row->nip_ketua . '<br/> Nama: ' . $row->nama_ketua . '<br/> Alamat: ' . $row->alamat_ketua;
                })
                ->editColumn('status', function ($row) {
                    $badge = ($row->status == 'proses' ? 'info' : ($row->status == 'dikunci' ? 'dark' : 'danger'));
                    return '<span class="badge bg-' . $badge . '"  style="white-space: normal;">' . $row->status . '</span>';
                })
                ->editColumn('tempat_pemusnahan', function ($row) {
                    return $row->tempat_pemusnahan . '<br/>' . $row->alamat_pemusnahan . '<br/>' . $row->kota_pemusnahan;
                })
                ->editColumn('file', function ($row) {
                    $button = '';
                    if ($row->file)
                        $button .= '<a class="mb-4" href="' . asset('storage/' . $row->file) . '" target="_blank">Lihat</a>';

                    if($row->status == 'dilaksanakan')
                        $button .= '<button class="btn btn-icon btn-text-primary" data-bs-toggle="modal" data-bs-target="#modalUploadBukti" data-kt-action="upload_bukti" data-id="' . $row->id . '" title="Tambah/Edit Bukti Pemusnahan"><i class="tf-icons ti ti-upload scaleX-n1-rtl ti-xs"></i></button>';

                    return $button;
                })
                ->addColumn('action', function($row){
                    $id = $row->id;
                    if (Gate::allows(Route::currentRouteName() . '.update')) {
                        $buttons = '<button class="btn btn-icon btn-text-success" data-bs-toggle="modal" data-bs-target="#modalSaksi" data-kt-action="edit_saksi" data-id="' . $id . '" title="Tambah/Edit Saksi"><i class="tf-icons ti ti-user-plus scaleX-n1-rtl ti-xs"></i></button>';
                        $buttons .= '<button class="btn btn-icon btn-text-info" data-bs-toggle="modal" data-bs-target="#modalPilihRM" data-kt-action="pilih_rm" data-id="' . $id . '" title="Pemilahan Data"><i class="tf-icons ti ti-list-check scaleX-n1-rtl ti-xs"></i></button>';

                        if($row->status == 'proses')
                            $buttons .= '<button class="btn btn-icon btn-text-dark" data-kt-action="kunci_ba" data-id="' . $id . '" title="Kunci"><i class="tf-icons ti ti-lock scaleX-n1-rtl ti-xs"></i></button>';
                        else if($row->status == 'dikunci'){
                            $buttons .= '<button class="btn btn-icon btn-text-dark" data-kt-action="buka_ba" data-id="' . $id . '" title="Buka Kunci"><i class="tf-icons ti ti-lock-open scaleX-n1-rtl ti-xs"></i></button>';
                            $buttons .= '<a href="' . route('admin.laporan-berita-acara.cetak', $id) . '" target="_blank" class="btn btn-icon btn-text-primary" title="Cetak"><i class="tf-icons ti ti-printer scaleX-n1-rtl ti-xs"></i></a>';
                            $buttons .= '<button class="btn btn-icon btn-text-danger" data-kt-action="musnahkan" data-id="' . $id . '" title="Musnahkan"><i class="tf-icons ti ti-flame scaleX-n1-rtl ti-xs"></i></button>';
                        }
                    } else {
                        $buttons = '';
                    }
                    if($row->status == 'proses')
                        $buttons .= view('components.master.table-button', compact('id'));

                    return $buttons;
                })
                ->rawColumns(['action','ketua','tempat_pemusnahan','status','file'])
                ->make(true);
        }
        return view('admin.berita-acara.index', compact('pageSetting'));
    }

    public function cetak($id){
        $title = 'BERITA ACARA PEMUSNAHAN BERKAS REKAM MEDIS';
        $format = 'html';
        $beritaAcara = BeritaAcaraPemusnahan::findOrFail($id);
        return view('admin.berita-acara.report', compact('title','format','beritaAcara'));
    }
}
