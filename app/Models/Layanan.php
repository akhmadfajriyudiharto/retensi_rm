<?php

namespace App\Models;

use App\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use SpatieLogsActivity;

    protected $guarded = ['id'];
}
