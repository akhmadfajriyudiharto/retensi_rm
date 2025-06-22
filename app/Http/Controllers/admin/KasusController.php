<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kasus;
use App\Models\Layanan;
use App\Models\RetensiRecord;
use Illuminate\Support\Facades\DB;
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

    /**
     * Display a listing of the resource.
     */
    public function rekapKasus()
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
            'total_aktif' => [
                'name'      => 'Aktif',
                'type'      => 'text',
                'rule'      => 'required|string|max:255',
                'isSearch'  => false
            ],
            'total_inaktif' => [
                'name'      => 'Inaktif',
                'type'      => 'text',
                'rule'      => 'required|string|max:255',
                'isSearch'  => false
            ],
            'total_dimusnahkan' => [
                'name'      => 'Dimusnahkan',
                'type'      => 'text',
                'rule'      => 'required|string|max:255',
                'isSearch'  => false
            ],
            'total_rekam_medis' => [
                'name'      => 'Total',
                'type'      => 'text',
                'rule'      => 'required|string|max:255',
                'isSearch'  => false
            ],
        ];
        $pageSetting = [
            'title'         => 'List Kasus',
            'model'         => \App\Models\Kasus::class,
            'routeName'     => 'admin.laporan-rekam-medis',
            'showActionColumn'  => false,
            'fields'         => $fields
        ];
        if (request()->ajax()) {
            $rekapSub = DB::table('rekam_medis')
                            ->join(DB::raw('(SELECT rekam_medis_id, status
                                            FROM retensi_records r1
                                            WHERE r1.created_at = (
                                                SELECT MAX(created_at)
                                                FROM retensi_records
                                                WHERE rekam_medis_id = r1.rekam_medis_id
                                            )) latest_retensi'),
                                'rekam_medis.id', '=', 'latest_retensi.rekam_medis_id'
                            )
                            ->select(
                                'rekam_medis.kasus_id',
                                DB::raw('COUNT(rekam_medis.id) as total_rekam_medis'),
                                DB::raw("SUM(CASE WHEN latest_retensi.status = '".RetensiRecord::STATUS_AKTIF."' THEN 1 ELSE 0 END) as total_aktif"),
                                DB::raw("SUM(CASE WHEN latest_retensi.status = '".RetensiRecord::STATUS_INAKTIF."' THEN 1 ELSE 0 END) as total_inaktif"),
                                DB::raw("SUM(CASE WHEN latest_retensi.status = '".RetensiRecord::STATUS_MUSNAH."' THEN 1 ELSE 0 END) as total_dimusnahkan")
                            )
                            ->groupBy('rekam_medis.kasus_id');

            $data = Kasus::select([
                        'kasuses.id',
                        'kasuses.nama',
                        'layanans.nama as layanan_id',
                        DB::raw('COALESCE(rekap.total_rekam_medis, 0) as total_rekam_medis'),
                        DB::raw('COALESCE(rekap.total_aktif, 0) as total_aktif'),
                        DB::raw('COALESCE(rekap.total_inaktif, 0) as total_inaktif'),
                        DB::raw('COALESCE(rekap.total_dimusnahkan, 0) as total_dimusnahkan'),
                    ])
                    ->join('layanans', 'layanans.id', '=', 'kasuses.layanan_id')
                    ->leftJoinSub($rekapSub, 'rekap', function ($join) {
                        $join->on('rekap.kasus_id', '=', 'kasuses.id');
                    });
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('aktif', function ($row) {
                    return $row->aktif . ' Tahun';
                })
                ->editColumn('inaktif', function ($row) {
                    return $row->inaktif . ' Tahun';
                })
                ->rawColumns([])
                ->make(true);
        }
        return view('components.master.index', compact('pageSetting'));
    }
}
