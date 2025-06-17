<?php

namespace App\Models;

use App\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraRekamMedis extends Model
{
    use SpatieLogsActivity;

    protected $guarded = ['id'];
}
