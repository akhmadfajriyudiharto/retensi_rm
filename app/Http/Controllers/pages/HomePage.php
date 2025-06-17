<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\RetensiRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomePage extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('content.pages.pages-home', compact('user'));
    }

    public function statistik()
    {
        $totalDokter = Dokter::count();
        $dokterPerJK = Dokter::select('jenis_kelamin', DB::raw('count(*) as jumlah'))
                    ->groupBy('jenis_kelamin')
                    ->pluck('jumlah', 'jenis_kelamin');
        $totalPasien = Pasien::count();
        $pasienPerJK = Pasien::select('jenis_kelamin', DB::raw('count(*) as jumlah'))
                    ->groupBy('jenis_kelamin')
                    ->pluck('jumlah', 'jenis_kelamin');
        $totalRekamMedis = RekamMedis::count();
        $rekamMedisPerStatus = RekamMedis::with('latestRetensi')
                    ->get()
                    ->groupBy(fn ($r) => RetensiRecord::getStatus($r->latestRetensi?->status) ?? 'Tidak Ada')
                    ->map(fn ($group) => $group->count());
        $rekamMedisPerStatusPerTahun = RekamMedis::with('latestRetensi')
                    ->where('tanggal_kunjungan', '>=', Carbon::now()->subYears(10))
                    ->get()
                    ->groupBy(function ($item) {
                        return (int) \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('Y');
                    })
                    ->map(function ($group) {
                        return $group->groupBy(function ($item) {
                            return RetensiRecord::getStatus($item->latestRetensi?->status) ?? 'Tidak Ada';
                        })->map->count();
                    });

        $tahunSekarang = now()->year;
        $tahunAwal = $tahunSekarang - 9;
        $tahunRange = range($tahunAwal, $tahunSekarang);

        $semuaStatus = $rekamMedisPerStatusPerTahun->flatMap(function ($tahunData) {
            return $tahunData->keys();
        })->unique()->values();

        $seriesData = [];
        foreach ($semuaStatus as $status) {
            $dataPerTahun = [];
            foreach ($tahunRange as $tahun) {
                $dataPerTahun[] = $rekamMedisPerStatusPerTahun[$tahun][$status] ?? 0;
            }
            $seriesData[] = [
                'name' => $status,
                'data' => $dataPerTahun
            ];
        }
        $data = [
            'totalDokter' => $totalDokter,
            'dokterPerJK' => $dokterPerJK,
            'totalPasien' => $totalPasien,
            'pasienPerJK' => $pasienPerJK,
            'totalRekamMedis' => $totalRekamMedis,
            'rekamMedisPerStatus' => $rekamMedisPerStatus,
            'seriesRM' => $seriesData,
            'categoriesRM' => $tahunRange,
        ];

        return view('content.pages.statistik', compact('data'));
    }
}
