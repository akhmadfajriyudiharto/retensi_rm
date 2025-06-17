<?php

namespace App\Models;

use App\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraPemusnahan extends Model
{
    use SpatieLogsActivity;

    protected $guarded = ['id'];

    public function saksi()
    {
        return $this->hasMany(SaksiPemusnahan::class);
    }

    public function rekamMedis()
    {
        return $this->belongsToMany(RekamMedis::class, 'berita_acara_rekam_medis');
    }
}
