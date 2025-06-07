<?php

namespace App\Models;

use App\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;

class RetensiRecord extends Model
{
    use SpatieLogsActivity;

    protected $guarded = ['id'];
    public const STATUS_AKTIF='A', STATUS_BELUM_INAKTIF='BI', STATUS_INAKTIF='I', STATUS_BELUM_MUSNAH='BM', STATUS_MUSNAH='M';

    public static function getAllStatus(){
        return [
            self::STATUS_AKTIF => 'AKTIF',
            self::STATUS_BELUM_INAKTIF => 'AKTIF (BELUM RETENSI)',
            self::STATUS_INAKTIF => 'INAKTIF',
            self::STATUS_BELUM_MUSNAH => 'INAKTIF (BELUM DIMUSNAHKAN)',
            self::STATUS_MUSNAH => 'DIMUSNAHKAN'
        ];
    }

    public static function getStatus($key)
    {
        return self::getAllStatus()[$key];
    }

    public static function getAllStatusBadge(){
        return [
            self::STATUS_AKTIF => 'success',
            self::STATUS_BELUM_INAKTIF => 'info',
            self::STATUS_INAKTIF => 'dark',
            self::STATUS_BELUM_MUSNAH => 'warning',
            self::STATUS_MUSNAH => 'danger'
        ];
    }

    public static function getStatusBadge($key)
    {
        return self::getAllStatusBadge()[$key];
    }

    public function getStatusLabelAttribute()
    {
        return $this->status ?? self::getAllStatus()[$this->status];
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class);
    }
}
