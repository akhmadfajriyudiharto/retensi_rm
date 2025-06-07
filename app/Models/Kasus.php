<?php

namespace App\Models;

use App\Traits\SpatieLogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Kasus extends Model
{
    use SpatieLogsActivity;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::updated(function ($kasus) {
            if ($kasus->wasChanged('aktif')) {
                $rekamMedis = RekamMedis::where('kasus_id', $kasus->id)
                    ->get()
                    ->filter(function ($rm) {
                        $latestStatus = $rm->retensiRecords()->latest()->value('status');
                        return $latestStatus === RetensiRecord::STATUS_AKTIF;
                    });

                foreach ($rekamMedis as $rm) {
                    $rm->update(['batas_aktif' => Carbon::parse($rm->tanggal_kunjungan)->addYears((int) $kasus->aktif ?? 0)]);
                }
            }

            if ($kasus->wasChanged('inaktif')) {
                $rekamMedis = RekamMedis::where('kasus_id', $kasus->id)
                    ->get()
                    ->filter(function ($rm) {
                        $latestStatus = $rm->retensiRecords()->latest()->value('status');
                        return in_array($latestStatus, [RetensiRecord::STATUS_AKTIF, RetensiRecord::STATUS_INAKTIF]);
                    });

                foreach ($rekamMedis as $rm) {
                    $rm->update(['batas_inaktif' => Carbon::parse($rm->tanggal_kunjungan)->addYears((int) $kasus->inaktif ?? 0)]);
                }
            }
        });
    }
}
