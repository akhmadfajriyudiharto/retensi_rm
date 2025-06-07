<?php

namespace App\Livewire\Admin\Pasien;

use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\RetensiRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RekamMedisModal extends Component
{
    public $pasien = null, $rekam_medis = null;
    public $listeners = ['viewRekamMedis'];

    public function viewRekamMedis($id)
    {
        $this->rekam_medis = null;
        $this->pasien = Pasien::find($id);
        $retensi = RetensiRecord::select(DB::raw('DISTINCT ON (rekam_medis_id) rekam_medis_id, status, created_at'))
                        ->orderBy('rekam_medis_id')
                        ->orderByDesc('created_at');
        $rekam_medis =  RekamMedis::select(['rekam_medis.id', 'rekam_medis.tanggal_kunjungan', 'rekam_medis.diagnosa',
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
                        ->where('rekam_medis.pasien_id', '=', $id)->get();
        foreach ($rekam_medis as $value) {
            $this->rekam_medis[$value->id]['tanggal_kunjungan'] = Carbon::parse($value->tanggal_kunjungan)->translatedFormat('d F Y');
            $this->rekam_medis[$value->id]['kasus'] = $value->nama_kasus;
            $this->rekam_medis[$value->id]['dokter'] = $value->dokter_id;
            $status = $value->status;

            if (in_array($status, [RetensiRecord::STATUS_AKTIF, RetensiRecord::STATUS_INAKTIF])) {
                $tanggalBatas = $status === RetensiRecord::STATUS_AKTIF
                    ? $value->batas_aktif
                    : $value->batas_inaktif;

                if ($tanggalBatas) {
                    $tanggalBatas = \Carbon\Carbon::parse($tanggalBatas);
                    if ($tanggalBatas->isPast()) {
                        $status = $status === RetensiRecord::STATUS_AKTIF
                            ? RetensiRecord::STATUS_BELUM_INAKTIF
                            : RetensiRecord::STATUS_BELUM_MUSNAH;
                    }
                }
            }
            $this->rekam_medis[$value->id]['status'] = RetensiRecord::getStatus($status);
        }
    }

    public function render()
    {
        return view('admin.pasien.rekam-medis-modal');
    }
}
