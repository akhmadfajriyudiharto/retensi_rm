<?php

namespace App\Models;

use App\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use SpatieLogsActivity;

    protected $guarded = ['id'];
}
