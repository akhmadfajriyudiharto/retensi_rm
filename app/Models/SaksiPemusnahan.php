<?php

namespace App\Models;

use App\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;

class SaksiPemusnahan extends Model
{
    use SpatieLogsActivity;

    protected $guarded = ['id'];

    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcaraPemusnahan::class);
    }
}
