<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Kasus;
use App\Models\Layanan;
use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\RetensiRecord;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RekamMedisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = [
            'no_rm' => [
                'name'      => 'No. RM',
                'type'      => 'text',
                'isTable'   => 'invisible',
                'isCreate'  => false,
                'isEdit'    => false
            ],
            'pasien_id' => [
                'name'      => 'Pasien',
                'type'      => 'select',
                'class'     => 'select2',
                'rule'      => 'required',
                'options'   => Pasien::all()->mapWithKeys(function ($pasien) {
                    return [$pasien->id => "{$pasien->no_rm} - {$pasien->nama}"];
                })->all()
            ],
            'layanan_id' => [
                'name'      => 'Layanan',
                'type'      => 'select',
                'rule'      => 'required',
                'options'   => Layanan::pluck('nama', 'id')->all()
            ],
            'kasus_id' => [
                'name'      => 'Kasus',
                'type'      => 'select',
                'class'     => 'select2',
                'rule'      => 'required',
                'options'   => Kasus::pluck('nama', 'id')->all(),
                'isTable'   => 'invisible'
            ],
            'nama_kasus' => [
                'name'      => 'Kasus',
                'type'      => 'text',
                'options'   => Kasus::pluck('nama', 'id')->all(),
                'isCreate'  => false,
                'isEdit'    => false,
                'isSearch'  => false
            ],
            'dokter_id' => [
                'name'      => 'Dokter',
                'type'      => 'select',
                'class'     => 'select2',
                'rule'      => 'required',
                'options'   => Dokter::all()->mapWithKeys(function ($dokter) {
                    return [$dokter->id => "{$dokter->kode_dokter} - {$dokter->gelar_depan} {$dokter->nama} {$dokter->gelar_belakang}"];
                })->all()
            ],
            'tanggal_kunjungan' => [
                'name'      => 'Tanggal Kunjungan',
                'type'      => 'date',
                'rule'      => 'required',
                'isSearch'  => false
            ],
            'diagnosa' => [
                'name'      => 'Diagnosa',
                'type'      => 'textarea',
                'rule'      => 'nullable',
                'isSearch'  => false,
                'isTable'   => false
            ],
            'tindakan' => [
                'name'      => 'Tindakan',
                'type'      => 'textarea',
                'rule'      => 'nullable',
                'isSearch'  => false,
                'isTable'   => false
            ],
            'file' => [
                'name'      => 'File Scanan',
                'type'      => 'file',
                'rule'      => 'required',
                'isSearch'  => false
            ],
            'status' => [
                'name'      => 'Status',
                'type'      => 'select',
                'options'   => RetensiRecord::getAllStatus(),
                'isCreate'  => false,
                'isEdit'    => false
            ],
        ];
        $pageSetting = [
            'title'         => 'List Rekam Medis',
            'model'         => \App\Models\RekamMedis::class,
            'routeName'     => 'admin.rekam-medis',
            'formWidth'     => 'fullscreen',
            'fieldWidth'    => 6,
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $retensi = RetensiRecord::select(DB::raw('DISTINCT ON (rekam_medis_id) rekam_medis_id, status'))
                            ->orderBy('rekam_medis_id')
                            ->orderByDesc('created_at');
            $data = RekamMedis::select(['rekam_medis.id', 'rekam_medis.tanggal_kunjungan', 'rekam_medis.diagnosa',
                            'rekam_medis.tindakan', 'rekam_medis.kasus_id', 'rekam_medis.batas_aktif',
                            'rekam_medis.batas_inaktif', 'pasiens.nama as pasien_id', 'pasiens.no_rm',
                            'rekam_medis.file', 'layanans.nama as layanan_id', 'kasuses.nama as nama_kasus',
                            'dokters.nama as dokter_id', 'retensi_records.status'])
                            ->join('pasiens', 'pasiens.id', 'rekam_medis.pasien_id')
                            ->join('layanans', 'layanans.id', 'rekam_medis.layanan_id')
                            ->join('kasuses', 'kasuses.id', 'rekam_medis.kasus_id')
                            ->join('dokters', 'dokters.id', 'rekam_medis.dokter_id')
                            ->leftJoinSub($retensi, 'retensi_records', function ($join) {
                                $join->on('retensi_records.rekam_medis_id', '=', 'rekam_medis.id');
                            });
            return DataTables::of($data)
                ->filterColumn('no_rm', function($query, $keyword) {
                    $query->where('pasiens.no_rm', 'ilike', "%{$keyword}%");
                })
                ->filterColumn('status', function($query, $keyword) {
                    if ($keyword == RetensiRecord::STATUS_BELUM_INAKTIF) {
                        $query->where('retensi_records.status', RetensiRecord::STATUS_AKTIF)
                              ->whereDate('rekam_medis.batas_aktif', '<', now());
                    } elseif ($keyword == RetensiRecord::STATUS_BELUM_MUSNAH) {
                        $query->where('retensi_records.status', RetensiRecord::STATUS_INAKTIF)
                              ->whereDate('rekam_medis.batas_inaktif', '<', now());
                    } else {
                        $query->where('retensi_records.status', '=', $keyword);
                    }
                })
                ->addIndexColumn()
                ->editColumn('pasien_id', function ($row) {
                    return $row->no_rm . '<br/>' . $row->pasien_id;
                })
                ->editColumn('status', function ($row)  {
                    $status = $row->status;

                    if (in_array($status, [RetensiRecord::STATUS_AKTIF, RetensiRecord::STATUS_INAKTIF])) {
                        $tanggalBatas = $status === RetensiRecord::STATUS_AKTIF
                            ? $row->batas_aktif
                            : $row->batas_inaktif;

                        if ($tanggalBatas) {
                            $tanggalBatas = \Carbon\Carbon::parse($tanggalBatas);
                            if ($tanggalBatas->isPast()) {
                                $status = $status === RetensiRecord::STATUS_AKTIF
                                    ? RetensiRecord::STATUS_BELUM_INAKTIF
                                    : RetensiRecord::STATUS_BELUM_MUSNAH;
                            }
                        }
                    }

                    return '<span class="badge bg-' . RetensiRecord::getStatusBadge($status) . '"  style="white-space: normal;">' . RetensiRecord::getStatus($status) . '</span>';
                })
                ->editColumn('tanggal_kunjungan', function ($row) {
                    return \Carbon\Carbon::parse($row->tanggal_kunjungan)->translatedFormat('d F Y');
                })
                ->editColumn('file', function ($row) {
                    return '<a class="mb-4" href="' . asset('storage/' . $row->file) . '" target="__blank">Lihat</a>';
                })
                ->addColumn('action', function($row){
                    $id = $row->id;
                    return view('components.master.table-button', compact('id'));
                })
                ->rawColumns(['action','file','pasien_id','status'])
                ->make(true);
        }
        return view('components.master.index', compact('pageSetting'));
    }
    /**
     * Display a listing of the resource.
     */
    public function retensi()
    {
        $fields = [
            'no_rm' => [
                'name'      => 'No. RM',
                'type'      => 'text',
                'isTable'   => 'invisible',
                'isCreate'  => false,
                'isEdit'    => false
            ],
            'pasien_id' => [
                'name'      => 'Pasien',
                'type'      => 'select',
                'class'     => 'select2',
                'rule'      => 'required',
                'options'   => Pasien::all()->mapWithKeys(function ($pasien) {
                    return [$pasien->id => "{$pasien->no_rm} - {$pasien->nama}"];
                })->all()
            ],
            'layanan_id' => [
                'name'      => 'Layanan',
                'type'      => 'select',
                'rule'      => 'required',
                'options'   => Layanan::pluck('nama', 'id')->all()
            ],
            'kasus_id' => [
                'name'      => 'Kasus',
                'type'      => 'select',
                'class'     => 'select2',
                'rule'      => 'required',
                'options'   => Kasus::pluck('nama', 'id')->all(),
                'isTable'   => 'invisible'
            ],
            'nama_kasus' => [
                'name'      => 'Kasus',
                'type'      => 'text',
                'options'   => Kasus::pluck('nama', 'id')->all(),
                'isCreate'  => false,
                'isEdit'    => false,
                'isSearch'  => false
            ],
            'dokter_id' => [
                'name'      => 'Dokter',
                'type'      => 'select',
                'class'     => 'select2',
                'rule'      => 'required',
                'options'   => Dokter::all()->mapWithKeys(function ($dokter) {
                    return [$dokter->id => "{$dokter->kode_dokter} - {$dokter->gelar_depan} {$dokter->nama} {$dokter->gelar_belakang}"];
                })->all()
            ],
            'tanggal_kunjungan' => [
                'name'      => 'Tanggal',
                'type'      => 'date',
                'rule'      => 'required',
                'isSearch'  => false
            ],
            'diagnosa' => [
                'name'      => 'Diagnosa',
                'type'      => 'textarea',
                'rule'      => 'nullable',
                'isSearch'  => false,
                'isTable'   => false
            ],
            'tindakan' => [
                'name'      => 'Tindakan',
                'type'      => 'textarea',
                'rule'      => 'nullable',
                'isSearch'  => false,
                'isTable'   => false
            ],
            'file' => [
                'name'      => 'File Scanan',
                'type'      => 'file',
                'rule'      => 'required',
                'isSearch'  => false
            ],
            'status' => [
                'name'      => 'Status',
                'type'      => 'select',
                'options'   => Arr::only(RetensiRecord::getAllStatus(), ['BI','I']),
                'isCreate'  => false,
                'isEdit'    => false
            ],
        ];
        $pageSetting = [
            'title'         => 'List Rekam Medis',
            'model'         => \App\Models\RekamMedis::class,
            'routeName'     => 'admin.retensi',
            'formWidth'     => 'fullscreen',
            'fieldWidth'    => 6,
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $retensi = RetensiRecord::select(DB::raw('DISTINCT ON (rekam_medis_id) rekam_medis_id, status, created_at'))
                            ->orderBy('rekam_medis_id')
                            ->orderByDesc('created_at');
            $data = RekamMedis::select(['rekam_medis.id', 'rekam_medis.tanggal_kunjungan', 'rekam_medis.diagnosa',
                            'rekam_medis.tindakan', 'rekam_medis.kasus_id', 'rekam_medis.batas_aktif',
                            'rekam_medis.batas_inaktif', 'pasiens.nama as pasien_id', 'pasiens.no_rm',
                            'rekam_medis.file', 'layanans.nama as layanan_id', 'kasuses.nama as nama_kasus',
                            'dokters.nama as dokter_id', 'retensi_records.status', 'retensi_records.created_at as tgl_retensi'])
                            ->join('pasiens', 'pasiens.id', 'rekam_medis.pasien_id')
                            ->join('layanans', 'layanans.id', 'rekam_medis.layanan_id')
                            ->join('kasuses', 'kasuses.id', 'rekam_medis.kasus_id')
                            ->join('dokters', 'dokters.id', 'rekam_medis.dokter_id')
                            ->leftJoinSub($retensi, 'retensi_records', function ($join) {
                                $join->on('retensi_records.rekam_medis_id', '=', 'rekam_medis.id');
                            })
                            ->whereIn('retensi_records.status', [RetensiRecord::STATUS_AKTIF, RetensiRecord::STATUS_INAKTIF])
                            ->whereDate('rekam_medis.batas_aktif', '<', now());
            return DataTables::of($data)
                ->filterColumn('no_rm', function($query, $keyword) {
                    $query->where('pasiens.no_rm', 'ilike', "%{$keyword}%");
                })
                ->filterColumn('status', function($query, $keyword) {
                    if ($keyword == RetensiRecord::STATUS_BELUM_INAKTIF) {
                        $query->where('retensi_records.status', RetensiRecord::STATUS_AKTIF)
                              ->whereDate('rekam_medis.batas_aktif', '<', now());
                    } elseif ($keyword == RetensiRecord::STATUS_BELUM_MUSNAH) {
                        $query->where('retensi_records.status', RetensiRecord::STATUS_INAKTIF)
                              ->whereDate('rekam_medis.batas_inaktif', '<', now());
                    } else {
                        $query->where('retensi_records.status', '=', $keyword);
                    }
                })
                ->addIndexColumn()
                ->editColumn('pasien_id', function ($row) {
                    return $row->no_rm . '<br/>' . $row->pasien_id;
                })
                ->editColumn('status', function ($row)  {
                    $status = $row->status;

                    if (in_array($status, [RetensiRecord::STATUS_AKTIF, RetensiRecord::STATUS_INAKTIF])) {
                        $tanggalBatas = $status === RetensiRecord::STATUS_AKTIF
                            ? $row->batas_aktif
                            : $row->batas_inaktif;

                        if ($tanggalBatas) {
                            $tanggalBatas = \Carbon\Carbon::parse($tanggalBatas);
                            if ($tanggalBatas->isPast()) {
                                $status = $status === RetensiRecord::STATUS_AKTIF
                                    ? RetensiRecord::STATUS_BELUM_INAKTIF
                                    : RetensiRecord::STATUS_BELUM_MUSNAH;
                            }
                        }
                    }

                    return '<span class="badge bg-' . RetensiRecord::getStatusBadge($status) . '"  style="white-space: normal;">' . RetensiRecord::getStatus($status) . '</span>';
                })
                ->editColumn('tanggal_kunjungan', function ($row) {
                    return 'Kunjungan: ' . Carbon::parse($row->tanggal_kunjungan)->translatedFormat('d F Y') .
                            '<br/> Retensi: ' . ($row->status === RetensiRecord::STATUS_INAKTIF ? Carbon::parse($row->tgl_retensi)->translatedFormat('d F Y') : '-');
                })
                ->editColumn('file', function ($row) {
                    return '<a class="mb-4" href="' . asset('storage/' . $row->file) . '" target="__blank">Lihat</a>';
                })
                ->addColumn('action', function($row){
                    $id = $row->id;

                    return $row->status === RetensiRecord::STATUS_AKTIF ? '<div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-warning" data-kt-action="retensi_row" data-id="' . $id . '">retensi</button>
                    </div>' : '';
                })
                ->rawColumns(['action','file','pasien_id','status','tanggal_kunjungan'])
                ->make(true);
        }
        return view('admin.retensi.index', compact('pageSetting'));
    }
}
