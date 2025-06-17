<?php

namespace App\Models;

use App\Traits\SpatieLogsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use SpatieLogsActivity;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::creating(function ($rekamMedis) {
            $kasus = Kasus::find($rekamMedis->kasus_id);

            if ($kasus) {
                $rekamMedis->batas_aktif = Carbon::parse($rekamMedis->tanggal_kunjungan)->addYears($kasus->aktif ?? 0);
                $rekamMedis->batas_inaktif = Carbon::parse($rekamMedis->tanggal_kunjungan)->addYears($kasus->inaktif ?? 0);
            }
        });

        static::created(function ($rekamMedis) {
            RetensiRecord::create(['rekam_medis_id' => $rekamMedis->id, 'status' => RetensiRecord::STATUS_AKTIF]);
        });

        static::updating(function ($rekamMedis) {
            if ($rekamMedis->isDirty('tanggal_kunjungan')) {
                $kasus = Kasus::find($rekamMedis->kasus_id);

                if ($kasus) {
                    $rekamMedis->batas_aktif = Carbon::parse($rekamMedis->tanggal_kunjungan)->addYears($kasus->aktif ?? 0);
                    $rekamMedis->batas_inaktif = Carbon::parse($rekamMedis->tanggal_kunjungan)->addYears($kasus->inaktif ?? 0);
                }
            }
        });
    }

    public function retensiRecords()
    {
        return $this->hasMany(RetensiRecord::class);
    }

    public function latestRetensi()
    {
        return $this->hasOne(RetensiRecord::class)->latestOfMany();
    }

    public function beritaAcaras()
    {
        return $this->belongsToMany(BeritaAcaraPemusnahan::class, 'berita_acara_rekam_medis');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }

    public function kasus()
    {
        return $this->belongsTo(Kasus::class);
    }
}
